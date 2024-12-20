import requests
import pandas as pd
from sqlalchemy import create_engine, text

# API URL và payload
API_URL = "https://stocktraders.vn/service/data/getTotalTradeReal"
PAYLOAD = {"TotalTradeRealRequest": {"account": "StockTraders"}}

def fetch_realtime_data():
    """Gọi API để lấy dữ liệu"""
    try:
        response = requests.post(API_URL, json=PAYLOAD)
        if response.status_code in [200, 201]:
            return response.json()
        else:
            print(f"Error: Received status code {response.status_code}")
    except Exception as e:
        print(f"Error while fetching data: {e}")
    return None

def get_historical_data(engine):
    """Truy vấn dữ liệu lịch sử từ cơ sở dữ liệu"""
    try:
        query = """
        SELECT ticker, date, close 
        FROM uptrends 
        WHERE ticker = 'VNINDEX' 
        ORDER BY date DESC 
        LIMIT 20
        """
        historical_data = pd.read_sql(query, con=engine)
        if historical_data.empty:
            print("No historical data found.")
        else:
            historical_data["date"] = pd.to_datetime(historical_data["date"])
        return historical_data.sort_values("date")  # Sắp xếp lại theo ngày tăng dần
    except Exception as e:
        print(f"Error fetching historical data: {e}")
        return pd.DataFrame()

def process_data(data, historical_data):
    """Lọc dữ liệu ticker VNINDEX và tính toán SMA20, EMA6"""
    stock_data = data.get("TotalTradeRealReply", {}).get("stockTotalReals", [])
    if not stock_data:
        print("Key 'stockTotalReals' not found in API response.")
        return None

    for stock in stock_data:
        if stock.get("ticker") == "VNINDEX":
            try:
                # Tạo DataFrame từ thông tin ticker VNINDEX
                df_new = pd.DataFrame([stock])
                df_new["date"] = pd.to_datetime(df_new["date"])

                # Kiểm tra nếu historical_data hoặc df_new rỗng
                if historical_data.empty:
                    combined_data = df_new
                else:
                    combined_data = pd.concat([historical_data, df_new]).sort_values("date").reset_index(drop=True)

                # Tính toán SMA20 và EMA6
                combined_data["SMA20"] = combined_data["close"].rolling(window=20).mean()
                combined_data["EMA6"] = combined_data["close"].ewm(span=6).mean().round(2)

                # Chỉ trả về dữ liệu mới đã tính toán (dòng cuối cùng)
                return combined_data.iloc[-1:][["ticker", "date", "close", "SMA20", "EMA6"]]
            except Exception as e:
                print(f"Error processing data for VNINDEX: {e}")
                return None

    print("Ticker 'VNINDEX' not found in stockTotalReals.")
    return None

def save_to_db(df, engine):
    """Xóa và thêm mới dữ liệu vào cơ sở dữ liệu."""
    try:
        with engine.connect() as connection:
            date_to_delete = df["date"].iloc[0].date()
            print(f"Data to delete for {date_to_delete}:")
            print(df)

            delete_query = """
            DELETE FROM uptrends
            WHERE ticker = :ticker AND date = :date
            """
            rows_deleted = connection.execute(
                text(delete_query),
                {"ticker": df["ticker"].iloc[0], "date": date_to_delete},
            ).rowcount
            connection.commit()  
            print(f"Rows deleted: {rows_deleted}.")

            print(f"Inserting new data for date: {date_to_delete}...")
            print("New data to insert:")
            print(df)

            df.to_sql("uptrends", con=engine, if_exists="append", index=False)
            print(f"Data inserted successfully. {len(df)} new rows added.")
    except Exception as e:
        print(f"Error while saving to database: {e}")

if __name__ == "__main__":
    print("Fetching data from API...")
    data = fetch_realtime_data()

    if data:
        print("Connecting to database...")
        engine = create_engine("mysql+pymysql://root:@my_app_emeralpha_mysql/sql_emeralphaai_")

        print("Fetching historical data from database...")
        historical_data = get_historical_data(engine)

        print("Processing data for VNINDEX...")
        df = process_data(data, historical_data)

        if df is not None:
            print("Saving VNINDEX data to database...")
            save_to_db(df, engine)
        else:
            print("Processed data is empty or invalid.")
    else:
        print("Failed to fetch data from API.")
