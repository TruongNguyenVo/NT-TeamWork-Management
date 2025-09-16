from mysql.connector import Error

import mysql.connector

def create_connection(host_name, user_name, user_password, db_name):
    connection = None
    try:
        connection = mysql.connector.connect(
            host=host_name,
            user=user_name,
            passwd=user_password,
            database=db_name
        )
        print("Connection to MySQL DB successful")
    except Error as e:
        print(f"The error '{e}' occurred")
    return connection

def execute_query(connection, query):
    cursor = connection.cursor()
    try:
        cursor.execute(query)
        result = cursor.fetchall()
        return result
    except Error as e:
        print(f"The error '{e}' occurred")
        return None


def query_predict_view(requestData):

    temp = []
    for key, value in requestData.items():
        temp.append(value)
    # Replace these variables with your MySQL server details
    host_name = "your_host"
    user_name = "your_username"
    user_password = "your_password"
    db_name = "your_database"

    # # Create a connection to the database
    # connection = create_connection(host_name, user_name, user_password, db_name)

    # query = "SELECT * FROM Predict"
    # results = execute_query(connection, query)
    
    # if results:
    #     for row in results:
    #         print(row)
    # return results

    # nay se tra ve cac gia tri cua cac record cua member
    temp = {
        "Vo Truong Nguyen" : [[8, 4, 2, 0.7, 0.6]],
        "Truong Nguyen ": [[7, 3, 1, 0.6, 0.5]],
        "Vo Nguyen": [[23,3,1,108.96,75.79]],
        "Vo Nguyen Truong": [[23,3,1,108.96,75.79]],
    }
    return temp