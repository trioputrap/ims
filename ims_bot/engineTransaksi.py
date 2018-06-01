import pymysql
import time
import cloudinary
import cloudinary.uploader
import uuid
import sqlite3

databaseBank = {
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "",
    "db": "ims_1",
    "cursorclass": pymysql.cursors.DictCursor
}

databasePdam = {
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "",
    "db": "ims_2",
    "cursorclass": pymysql.cursors.DictCursor
}

con_bank = pymysql.connect(**databaseBank)
c_bank = con_bank.cursor()

con_pdam = pymysql.connect(**databasePdam)
c_pdam = con_pdam.cursor()

cloudinary.config(
  cloud_name = 'wahyupermadie',
  api_key = '446675357838799',
  api_secret = 'lMc40iES4XObL29Pnqz_MwINfeE'
)

def dict_factory(cursor, row):
    d = {}
    for idx, col in enumerate(cursor.description):
        d[col[0]] = row[idx]
    return d

con_sqlite = sqlite3.connect(r"db/transaksiSqlite.db")
con_sqlite.row_factory = dict_factory
c_sqlite = con_sqlite.cursor()

def createDb():
    c_sqlite.execute('''CREATE TABLE transaksi (
        id bigint,
        transaksi_pdam bigint,
        no_pdam text,
        bulan text,
        tahun text,
        tanggal_bayar timestamp,
        bukti_pembayaran text,
        chat_id text,
        jumlah_tagihan integer,
        flag text
        );''')
    con_sqlite.commit()

def checkPdam():
    print("Check")
    c_pdam.execute("SELECT * FROM pembayarans WHERE flag='just_arrived'")
    data_bayar = c_pdam.fetchall()
    for data in data_bayar:
        c_pdam.execute("SELECT * FROM `pelanggans` WHERE `id`=%i"% data['pelanggan_id'])
        user = c_pdam.fetchone()
        print(user)
        c_bank.execute("INSERT INTO `transaksi`(`transaksi_pdam`,`no_pdam`,`bulan`,`tahun`,`chat_id`,`jumlah_tagihan`) VALUES "
                       "(%i,%i,%i,%i,'%s',%i)"%(data['id'],user['no_rek'],data['bulan'],data['tahun'],user['chat_id'],data['jumlah_tagihan']))
        con_bank.commit()

        c_bank.execute("SELECT LAST_INSERT_ID()")
        id = c_bank.fetchone()

        c_sqlite.execute("INSERT INTO `transaksi`(`id`,`transaksi_pdam`,`no_pdam`,`bulan`,`tahun`,`chat_id`,`jumlah_tagihan`) VALUES "
                       "(%i,%i,%i,%i,%i,'%s',%i)"%(id['LAST_INSERT_ID()'],data['id'],user['no_rek'],data['bulan'],data['tahun'],user['chat_id'],data['jumlah_tagihan']))
        con_sqlite.commit()

        c_pdam.execute("UPDATE `pembayarans` SET `flag`='processed' WHERE `id`=%i"%data['id'])
        con_pdam.commit()

    con_bank.rollback()
    con_pdam.rollback()

def checkDataBankSqlite():
    c_bank.execute("SELECT * FROM transaksi")
    data_bank = c_bank.fetchall()
    for bank_data in data_bank:
        c_bank.execute("SELECT CONCAT(`id`,`transaksi_pdam`,`no_pdam`,`bulan`,`tahun`,`tanggal_bayar`,bukti_pembayaran,"
                       "`chat_id`,`jumlah_tagihan`,`flag`) as `concatBank` FROM `transaksi` WHERE `id`='%s'"%str(bank_data['id']))
        bank = c_bank.fetchone()

        c_sqlite.execute("SELECT (`id`||`transaksi_pdam`||`no_pdam`||`bulan`||`tahun`||`tanggal_bayar`||`bukti_pembayaran`||`chat_id`"
                    "||`jumlah_tagihan`||`flag`) as `concatSqlite` FROM `transaksi` WHERE `id`='%s'"%str(bank_data['id']))
        sqlites = c_sqlite.fetchone()

        bank_row = bank['concatBank']
        bank_copy = sqlites['concatSqlite']

        if bank_row == bank_copy:
            print("DATA SAMA")
        else:
            print("Data Berbeda, Akan Disamakan")
            c_sqlite.execute("UPDATE `transaksi` SET `transaksi_pdam`='%s' WHERE `id`='%s'"%(
                str(bank_data['transaksi_pdam']),bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `no_pdam`='%s' WHERE `id`='%s'" % (
                str(bank_data['no_pdam']), bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `bulan`='%s' WHERE `id`='%s'" % (
                str(bank_data['bulan']), bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `tahun`='%s' WHERE `id`='%s'" % (
                str(bank_data['tahun']), bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `tanggal_bayar`='%s' WHERE `id`='%s'" % (
                str(bank_data['tanggal_bayar']), bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `bukti_pembayaran`='%s' WHERE `id`='%s'" % (
                str(bank_data['bukti_pembayaran']), bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `chat_id`='%s' WHERE `id`='%s'" % (
                str(bank_data['chat_id']), bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `jumlah_tagihan`='%s' WHERE `id`='%s'" % (
                str(bank_data['jumlah_tagihan']), bank_data['id']))
            con_sqlite.commit()

            c_sqlite.execute("UPDATE `transaksi` SET `flag`='%s' WHERE `id`='%s'" % (
                str(bank_data['flag']), bank_data['id']))
            con_sqlite.commit()

            print("Update Sukses")

    con_sqlite.rollback()
    con_bank.rollback()

def checkDataBankPdam():
    c_bank.execute("SELECT * FROM transaksi")
    data_bank = c_bank.fetchall()
    for bank_data in data_bank:
        c_bank.execute("SELECT CONCAT(`bukti_pembayaran`,`tanggal_bayar`) as `transaksiBank` FROM `transaksi` WHERE `transaksi_pdam`='%s'"%
                       bank_data['transaksi_pdam'])
        bank = c_bank.fetchone()['transaksiBank']

        c_pdam.execute("SELECT CONCAT(`bukti_trf`,`tanggal_bayar`) as `transaksiPdam` FROM `pembayarans` WHERE `id`='%s'"%
                       bank_data['transaksi_pdam'])
        pdam = c_pdam.fetchone()['transaksiPdam']

        # print(bank)
        # print(pdam)
        if bank == pdam:
            print("Data sama di bank dan pdam")
            # print(bank_data['transaksi_pdam'])
        else:
            print(bank_data['transaksi_pdam'])
            c_pdam.execute("UPDATE `pembayarans` SET `bukti_trf`='%s' WHERE `id`='%s'" % (
                str(bank_data['bukti_pembayaran']), bank_data['transaksi_pdam']))
            con_pdam.commit()

            c_pdam.execute("UPDATE `pembayarans` SET `tanggal_bayar`='%s' WHERE `id`='%s'" % (
                str(bank_data['tanggal_bayar']), bank_data['transaksi_pdam']))
            con_pdam.commit()

            print("Sukses Update di Bank dan Pdam")
    con_bank.rollback()
    con_pdam.rollback()
def main():
    while True:
        checkPdam()
        checkDataBankSqlite()
        checkDataBankPdam()
        time.sleep(1)
    
    

if __name__ == "__main__":
    main()