import requests
import pandas as pd
from sqlalchemy import create_engine, text

# Kết nối tới database
DATABASE_URI = "mysql+pymysql://root:@my_app_emeralpha_mysql/sql_emeralphaai_"
engine = create_engine(DATABASE_URI)

def fetch_data_from_api(url, payload):
    """
    Gửi request đến API và lấy dữ liệu JSON trả về.
    """
    try:
        response = requests.post(url, json=payload)
        if response.status_code in [200, 201]:
            return response.json()
        else:
            print(f"Error: {response.status_code}")
            return None
    except Exception as e:
        print(f"Error occurred: {e}")
        return None

def process_and_combine_data(historical_data, realtime_data):
    """
    Kết hợp dữ liệu lịch sử và realtime:
    - Tính SMA10, price_change_percentage, vol_to_SMA10_percentage.
    - Lọc dữ liệu theo các điều kiện.
    """
    # Xử lý dữ liệu lịch sử
    historical_totals = historical_data.get("TotalTradeReply", {}).get("stockTotals", [])
    historical_df_list = []

    for stock in historical_totals:
        ticker = stock.get("ticker", "Unknown")
        stock_type = stock.get("type", "Unknown")
        total_datas = stock.get("totalDatas", [])
        df = pd.DataFrame(total_datas)

        if not df.empty:
            df["date"] = pd.to_datetime(df["date"])
            df["ticker"] = ticker
            df["type"] = stock_type
            historical_df_list.append(df)

    historical_df = pd.concat(historical_df_list, ignore_index=True) if historical_df_list else pd.DataFrame()

    # Xử lý dữ liệu realtime
    realtime_totals = realtime_data.get("TotalTradeRealReply", {}).get("stockTotalReals", [])
    realtime_df = pd.DataFrame(realtime_totals)
    if not realtime_df.empty:
        realtime_df["date"] = pd.to_datetime("today").normalize()

    # Kết hợp dữ liệu lịch sử và realtime
    combined_df = pd.concat([historical_df, realtime_df], ignore_index=True)
    combined_df.sort_values(by=["ticker", "date"], inplace=True)

    # Tính SMA10
    combined_df["SMA10"] = combined_df.groupby("ticker")["vol"].transform(lambda x: x.rolling(window=10, min_periods=1).mean())

    # Tính price_change_percentage
    combined_df["price_change_percentage"] = combined_df.groupby("ticker")["close"].pct_change() * 100

    # Tính vol_to_SMA10_percentage
    combined_df["vol_to_SMA10_percentage"] = (combined_df["vol"] / combined_df["SMA10"]) * 100

    # Lọc dữ liệu theo điều kiện
    filtered_df = combined_df[
        (combined_df["price_change_percentage"] >= 2) &
        (combined_df["SMA10"] >= 200000) &
        (combined_df["vol_to_SMA10_percentage"] >= 150)
    ]

    # Chỉ giữ lại dữ liệu của ngày hôm nay
    today = pd.to_datetime("today").normalize()
    filtered_df = filtered_df[filtered_df["date"] == today]

    # Chọn các cột cần thiết
    filtered_df = filtered_df[[
        "date", "ticker", "type", "close", "price_change_percentage", "SMA10", "vol", "vol_to_SMA10_percentage"
    ]]

    return filtered_df

def update_database_with_realtime(dataframe):
    """
    Cập nhật dữ liệu vào database:
    - Xóa dữ liệu cũ của ngày hôm nay (nếu có).
    - Thêm dữ liệu mới đã được tính toán.
    """
    if dataframe is not None and not dataframe.empty:
        try:
            with engine.connect() as connection:
                # Lấy ngày hiện tại từ dataframe
                current_date = dataframe["date"].iloc[0]

                # Xóa dữ liệu cũ của ngày hiện tại
                delete_query = "DELETE FROM TOP15 WHERE `date` = :date"
                rows_deleted = connection.execute(
                    text(delete_query), {"date": current_date}
                ).rowcount
                print(f"{rows_deleted} rows deleted for date {current_date}.")

                # Thêm dữ liệu mới
                dataframe.to_sql("TOP15", con=connection, if_exists="append", index=False)
                print(f"Data for date {current_date} inserted successfully.")

                # Commit thay đổi
                connection.commit()

                # In dữ liệu mới được thêm vào
                print("Inserted data:")
                print(dataframe)
        except Exception as e:
            print(f"Error updating database: {e}")
    else:
        print("No data to update.")

# Main flow
if __name__ == "__main__":
    # Gọi API lịch sử
    historical_url = "https://stocktraders.vn/service/data/getTotalTrade"
    historical_payload = {"TotalTradeRequest": {"account": "StockTraders"}}
    historical_data = fetch_data_from_api(historical_url, historical_payload)

    # Gọi API realtime
    realtime_url = "https://stocktraders.vn/service/data/getTotalTradeReal"
    realtime_payload = {"TotalTradeRealRequest": {"account": "StockTraders"}}
    realtime_data = fetch_data_from_api(realtime_url, realtime_payload)

    if historical_data and realtime_data:
        # Kết hợp và xử lý dữ liệu
        processed_data = process_and_combine_data(historical_data, realtime_data)

        # Cập nhật vào database
        update_database_with_realtime(processed_data)
