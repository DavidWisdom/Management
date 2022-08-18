import xlrd
import csv

def xlsx_to_csv():
    workbook = xlrd.open_workbook('1.xlsx')
    table = workbook.sheet_by_index(0)
    with codecs.open('1.csv', 'w', encoding='utf-8') as f:
        write = csv.writer(f)
        for row_num in range(table.nrows):
            row_value = table.row_values(row_num)
            write.writerow(row_value)

if __name__ == '__main__':
    xlsx_to_csv()
