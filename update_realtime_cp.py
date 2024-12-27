import pandas as pd
import requests
from sqlalchemy import create_engine
from sqlalchemy import text

connection_string = f"mysql+pymysql://root:@my_app_emeralpha_mysql/sql_emeralphaai_"
engine = create_engine(connection_string)

# URLs và payloads
API_REALTIME_URL = "https://stocktraders.vn/service/data/getTotalTradeReal"
PAYLOAD_REALTIME = {"TotalTradeRealRequest": {"account": "StockTraders"}}

# Danh sách các nhóm ngành và chỉ số
categories = {
    "VN30": ["ACB", "BCM", "BID", "BVH", "CTG", "FPT", "GAS", "GVR", "HDB", "HPG", "MBB", "MSN", "MWG", "PLX", "POW", "SAB", "SHB", "SSB", "SSI", "STB", "TCB", "TPB", "VCB", "VHM", "VIB", "VIC", "VJC", "VNM", "VPB", "VRE"],
    "VN50": ["ACB", "CTG", "DCM", "DGC", "DIG", "DPM", "FPT", "FRT", "GEX", "GMD", "HCM", "HDB", "HPG", "HSG", "KBC", "KDH", "LPB", "MBB", "MSB", "MSN", "MWG", "NLG", "PDR", "PVD", "PVS", "SHB", "SSI", "STB", "TCB", "TPB", "VCB", "VCG", "VCI", "VHM", "VIB", "VIC", "VJC", "VND", "VNM", "VPB", "VPI", "VRE"],
    "bank": ["ACB", "BID", "CTG", "EIB", "LPB", "MBB", "VPB", "SHB", "STB", "VCB", "VIB", "TCB", "HDB", "MSB", "OCB", "TPB", "ABB", "BVB"],
    "securities": ["AGR", "BSI", "BVS", "CTS", "FTS", "HCM", "MBS", "SHS", "SSI", "TVS", "VCI", "VDS", "VIX", "VND"],
    "realEstate": ["PDR", "KDH", "KBC", "DIG", "NLG", "DXG", "HDG", "TCH", "CEO", "SZC", "HDC", "SCR", "NTL", "VHM", "VPI", "AGG", "CRE", "HPX", "VRE"],
    "retail": ["DGW", "FRT", "HTM", "MWG", "PET", "SAB", "MSN", "VNM", "QNS", "MCH", "KDC", "PAN"],
    "tech": ["FPT", "VTP", "CMG", "ELC", "ITD", "FOX", "VGI", "YEG", "TTN", "MFS"],
    "chemical": ["PVD", "GAS", "PVS", "PVT", "DGC", "DPM", "DCM", "OIL", "PLX", "BSR", "PVB", "CSV", "DDV"],
    "steelConstruction": ["HPG", "HSG", "NKG", "TLS", "TVN", "VGS", "CTD", "VCG", "FCN", "KSB", "PLC", "PC1", "CII", "DPG", "TV2", "LCG", "C32", "C4G"],
    "importExport": ["VCS", "TCM", "TNG", "VGT", "VGG", "GIL", "DRC", "GMD", "HAH", "ANV", "VHC", "FMC", "VSC", "ASM", "IDI"]
}

# Hàm lấy dữ liệu từ bảng `dataTotal`
def fetch_historical_data(engine):
    query = """
    SELECT ticker, date, close
    FROM dataTotal
    """
    with engine.connect() as connection:
        historical_df = pd.read_sql(query, connection)
    historical_df["date"] = pd.to_datetime(historical_df["date"])
    return historical_df

# Hàm lấy dữ liệu từ API
def fetch_realtime_data(api_url, payload):
    try:
        response = requests.post(api_url, json=payload)
        if response.status_code in [200, 201]:
            return response.json()
        else:
            print(f"Lỗi lấy dữ liệu từ API: {response.status_code}")
            return None
    except Exception as e:
        print(f"Lỗi khi gọi API: {e}")
        return None

# Hàm tính toán các chỉ số SMA và tỷ lệ vượt SMA
def calculate_sma_and_percentages(historical_data, realtime_data):
    # Chuyển đổi dữ liệu realtime thành DataFrame
    realtime_df = pd.DataFrame(realtime_data.get("TotalTradeRealReply", {}).get("stockTotalReals", []))
    if not realtime_df.empty:
        # Đảm bảo cột `date` trong dữ liệu realtime là kiểu `pd.Timestamp`
        realtime_df["date"] = pd.to_datetime(realtime_df["date"])
    else:
        raise ValueError("Dữ liệu realtime không hợp lệ.")

    # Chỉ lấy ngày mới nhất từ dữ liệu realtime
    latest_date = realtime_df["date"].max()
    print(f"Ngày mới nhất từ API realtime: {latest_date}")

    # Kết hợp dữ liệu quá khứ và realtime
    # Đảm bảo cột `date` trong dữ liệu quá khứ cũng là kiểu `pd.Timestamp`
    historical_data["date"] = pd.to_datetime(historical_data["date"])
    combined_df = pd.concat([historical_data, realtime_df]).drop_duplicates(subset=["ticker", "date"])
    combined_df.sort_values(by=["ticker", "date"], inplace=True)

    # Tính SMA
    combined_df["SMA20"] = combined_df.groupby("ticker")["close"].transform(lambda x: x.rolling(window=20).mean())
    combined_df["SMA50"] = combined_df.groupby("ticker")["close"].transform(lambda x: x.rolling(window=50).mean())
    combined_df["SMA100"] = combined_df.groupby("ticker")["close"].transform(lambda x: x.rolling(window=100).mean())
    combined_df["SMA200"] = combined_df.groupby("ticker")["close"].transform(lambda x: x.rolling(window=200).mean())

    # Lọc dữ liệu chỉ cho ngày mới nhất
    latest_data = combined_df[combined_df["date"] == latest_date]

    # Tính tỷ lệ vượt SMA
    result = {
        "date": latest_date,
        "CP_SMA20": (latest_data["close"] > latest_data["SMA20"]).mean() * 100,
        "CP_SMA50": (latest_data["close"] > latest_data["SMA50"]).mean() * 100,
        "CP_SMA100": (latest_data["close"] > latest_data["SMA100"]).mean() * 100,
        "CP_SMA200": (latest_data["close"] > latest_data["SMA200"]).mean() * 100
    }

    # Tính tỷ lệ vượt SMA cho từng nhóm ngành
    for category, tickers in categories.items():
        category_group = latest_data[latest_data["ticker"].isin(tickers)]
        if not category_group.empty:
            result[f"{category}_SMA20"] = (category_group["close"] > category_group["SMA20"]).mean() * 100
        else:
            result[f"{category}_SMA20"] = 0

    return pd.DataFrame([result])

def upsert_to_mysql(df, table_name, engine):
    """Xóa dữ liệu cũ cho ngày trước khi thêm mới."""
    try:
        # Làm tròn các giá trị số liệu đến 2 chữ số thập phân
        df = df.round(2)

        with engine.connect() as connection:
            for _, row in df.iterrows():
                # Lấy phần ngày (chỉ YYYY-MM-DD)
                row_date = row["date"]
                print(f"Processing date: {row_date}")
                print(f"Data being processed: {row.to_dict()}")

                # Xóa dữ liệu cũ không cần kiểm tra
                print(f"Deleting old data for date: {row_date}")
                query_delete = f"""
                DELETE FROM {table_name}
                WHERE `date` = :date
                """
                rows_deleted = connection.execute(
                    text(query_delete),
                    {"date": row_date}
                ).rowcount  # Đếm số dòng đã xóa

                print(f"Rows deleted: {rows_deleted}")

                # Thực hiện chèn dữ liệu mới
                print(f"Inserting data for date: {row_date}")
                query_insert = f"""
                INSERT INTO {table_name} (
                    `date`, CP_SMA20, CP_SMA50, CP_SMA100, CP_SMA200,
                    {', '.join([f"`{category}_SMA20`" for category in categories.keys()])}
                ) VALUES (
                    :date, :CP_SMA20, :CP_SMA50, :CP_SMA100, :CP_SMA200,
                    {', '.join([f":{category}_SMA20" for category in categories.keys()])}
                )
                """
                connection.execute(
                    text(query_insert),
                    {
                        "date": row_date,
                        "CP_SMA20": row["CP_SMA20"],
                        "CP_SMA50": row["CP_SMA50"],
                        "CP_SMA100": row["CP_SMA100"],
                        "CP_SMA200": row["CP_SMA200"],
                        **{f"{category}_SMA20": row[f"{category}_SMA20"] for category in categories.keys()}
                    }
                )
            
            connection.commit()
            print("Changes committed successfully!")
    except Exception as e:
        print(f"Lỗi khi cập nhật dữ liệu: {e}")


# Chạy chương trình
if __name__ == "__main__":
    print("Lấy dữ liệu quá khứ từ bảng dataTotal...")
    historical_data = fetch_historical_data(engine)

    print("Lấy dữ liệu realtime từ API...")
    realtime_data = fetch_realtime_data(API_REALTIME_URL, PAYLOAD_REALTIME)

    if not historical_data.empty and realtime_data:
        print("Tính toán SMA và tỷ lệ vượt SMA...")
        result_df = calculate_sma_and_percentages(historical_data, realtime_data)

        if not result_df.empty:
            print("Cập nhật dữ liệu vào bảng Daily_Percentages_on_SMA...")
            upsert_to_mysql(result_df, "Daily_Percentages_on_SMA", engine)
        else:
            print("Không có dữ liệu để lưu.")
    else:
        print("Không thể lấy dữ liệu từ API hoặc dữ liệu quá khứ.")
