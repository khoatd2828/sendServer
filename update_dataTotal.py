import pandas as pd
from sqlalchemy import create_engine, text
import time
import pymysql

# Kết nối cơ sở dữ liệu
DB_CONNECTION_STRING = "mysql+pymysql://root:@my_app_emeralpha_mysql/sql_emeralphaai_"
engine = create_engine(DB_CONNECTION_STRING)

# Tên file CSV và bảng
CSV_FILE = "raw_data.csv"
TABLE_NAME = "dataTotal"
MAX_ROWS = 399023  # Giới hạn số dòng cần chèn

# Hàm đợi MySQL sẵn sàng
def wait_for_mysql(host="my_app_emeralpha_mysql", port=3306, retries=30, delay=2):
    for _ in range(retries):
        try:
            conn = pymysql.connect(host=host, port=port, user="root", password="", database="sql_emeralphaai_")
            conn.close()
            print("MySQL is ready.")
            return True
        except pymysql.MySQLError:
            print("Waiting for MySQL to be ready...")
            time.sleep(delay)
    print("MySQL is not ready after waiting.")
    return False

# Hàm lấy số dòng đã tồn tại trong bảng
def get_row_count(table_name):
    with engine.connect() as connection:
        result = connection.execute(text(f"SELECT COUNT(*) FROM {table_name}")).scalar()
        return result

# Hàm tải dữ liệu từ CSV vào database
def load_csv_to_db(csv_file, table_name, chunksize=500):
    try:
        print(f"Reading data from {csv_file} in chunks of {chunksize} rows...")

        # Kiểm tra số dòng đã tồn tại trong bảng
        current_row_count = get_row_count(table_name)
        print(f"Current row count in {table_name}: {current_row_count}")

        for chunk_index, chunk in enumerate(pd.read_csv(csv_file, chunksize=chunksize, header=None, names=["date", "ticker", "close"])):
            # Nếu số dòng hiện tại đã đạt đến MAX_ROWS, dừng việc chèn
            if current_row_count >= MAX_ROWS:
                print(f"Reached {MAX_ROWS} rows. Stopping data insertion.")
                break

            # Chuyển đổi cột 'date' sang datetime
            chunk["date"] = pd.to_datetime(chunk["date"])

            # Lưu đợt dữ liệu vào cơ sở dữ liệu
            with engine.connect() as connection:
                try:
                    chunk.to_sql(name=table_name, con=connection, if_exists='append', index=False, method='multi')
                    current_row_count += len(chunk)
                    print(f"Chunk {chunk_index + 1} inserted successfully with {len(chunk)} rows. Total rows: {current_row_count}")
                except Exception as e:
                    print(f"Failed to insert chunk {chunk_index + 1}: {e}")

        print("All data inserted successfully or limit reached.")
    except Exception as e:
        print(f"Error while processing data: {e}")

if __name__ == "__main__":
    # Đợi MySQL sẵn sàng trước khi bắt đầu
    if wait_for_mysql():
        load_csv_to_db(CSV_FILE, TABLE_NAME)
    else:
        print("Exiting due to MySQL connection failure.")
