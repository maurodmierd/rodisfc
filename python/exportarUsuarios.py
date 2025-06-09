import pymysql
import openpyxl

conexion = pymysql.connect(
    host="localhost",
    user="rodisfc",
    password="",
    database="rodisfc"
)

try:
    with conexion.cursor() as cursor:
        cursor.execute("SELECT * FROM usuarios")
        columnas = [desc[0] for desc in cursor.description]
        filas = cursor.fetchall()

    wb = openpyxl.Workbook()
    ws = wb.active
    ws.title = "Usuarios"
    ws.append(columnas)

    for fila in filas:
        ws.append(fila)

    wb.save("usuarios.xlsx")

finally:
    conexion.close()
