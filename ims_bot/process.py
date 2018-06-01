import sqlite3

import pymysql
import time
import ngram
import telepot
import cloudinary
import cloudinary.uploader
import uuid
import datetime

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

con_sqlite = sqlite3.connect(r"db/transaksiSqlite.db")
c_sqlite = con_sqlite.cursor()

BOT = telepot.Bot('573076952:AAGd8uvyRiPoF-UhjNDY4_DdO57bfDL0Eoo')

cloudinary.config(
  cloud_name = 'wahyupermadie',
  api_key = '446675357838799',
  api_secret = 'lMc40iES4XObL29Pnqz_MwINfeE'
)

def enginePesan():
    con_bank = pymysql.connect(**databaseBank)
    c_bank = con_bank.cursor()

    c_bank.execute("select * from commands")
    command = c_bank.fetchall()
    commandList = []
    for cmd in command:
        commandList.append(cmd['command'])
    G = ngram.NGram(commandList)
    c_bank.execute("SELECT * FROM inboxes WHERE flag = 'received' order by id asc limit 1")
    pesan = c_bank.fetchone()
    if(pesan is not None):
        print(pesan['messages'])
        newCommand = G.search(pesan['messages'],threshold=0.24)
        # print(newCommand)
        if pesan['messages'] == 'cek tarif':
            c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                         (pesan['id'], pesan['chat_id'], 'Tarif Anda Adalah Rp 100900'))
            con_bank.commit()

            c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                         (pesan['id'], pesan['chat_id']))
            con_bank.commit()
        elif newCommand == [] and pesan['messages'] != 'cek tarif':
            c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                         (pesan['id'], pesan['chat_id'], 'Bot tidak mengetahui apa yang anda maksud'))
            con_bank.commit()

            c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                         (pesan['id'], pesan['chat_id']))
            con_bank.commit()
        elif newCommand[0][0] == 'register':
            c_bank.execute("select * from commands where `command` = '%s'" % newCommand[0][0])
            replay = c_bank.fetchone()

            c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                         (pesan['id'], pesan['chat_id'], replay['messages']+str(pesan['chat_id'])))
            con_bank.commit()

            c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                         (pesan['id'], pesan['chat_id']))
            con_bank.commit()
        elif newCommand[0][0] == 'transfer' or newCommand[0][0] == 'trf' and pesan['picture_id'] is not ' ':
            c_bank.execute("SELECT * FROM `transaksi` WHERE `no_pdam`='%s' AND `bulan`='%s' AND `tahun`='%s'"%
                           (pesan['pdam_number'],str(pesan['bulan']),str(pesan['tahun'])))
            pdam_number = c_bank.fetchone()
            if pdam_number is not None:
                c_pdam.execute("SELECT `pelanggan_id` FROM `pembayarans` WHERE `id`='%s'"%pdam_number['transaksi_pdam'])
                pelangganid = c_pdam.fetchone()['pelanggan_id']

                c_pdam.execute("SELECT * FROM `pelanggans` WHERE `id`='%s'"%pelangganid)
                pelanggan_chatid = c_pdam.fetchone()['chat_id']

                if str(pelanggan_chatid) == str(pesan['chat_id']):
                    c_bank.execute("select * from commands where `command` = '%s'" % newCommand[0][0])
                    replay = c_bank.fetchone()

                    c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                                   (pesan['id'], pesan['chat_id'], replay['messages']))
                    con_bank.commit()

                    c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                                   (pesan['id'], pesan['chat_id']))
                    con_bank.commit()

                    namaFile = str(pesan['picture_id']) + '.jpg'
                    BOT.download_file(pesan['picture_id'], 'foto/%s' + namaFile)

                    data = uuid.uuid4().hex
                    cloudinary.uploader.upload("foto/%s" + namaFile,
                                               public_id=data)

                    current_time = datetime.datetime.now()
                    c_bank.execute("UPDATE `transaksi` SET `bukti_pembayaran`='%s' WHERE `no_pdam`='%s' AND `bulan`='%s' AND `tahun`='%s'" %
                                   (str(data), pesan['pdam_number'],pesan['bulan'],pesan['tahun']))
                    con_bank.commit()

                    c_bank.execute("UPDATE `transaksi` SET `flag`='proces' WHERE `no_pdam`='%s' AND `bulan`='%s' AND `tahun`='%s'" %
                                   (pesan['pdam_number'],pesan['bulan'],pesan['tahun']))
                    con_bank.commit()

                    c_bank.execute("UPDATE `transaksi` SET `tanggal_bayar`='%s' WHERE `no_pdam`='%s'" %
                                   (str(current_time), pesan['pdam_number']))
                    con_bank.commit()
                else:
                    c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                                   (pesan['id'], pesan['chat_id'],
                                    "akun anda tidak terverifikasi"))
                    con_bank.commit()

                    c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                                   (pesan['id'], pesan['chat_id']))
                    con_bank.commit()
            else:
                c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                               (pesan['id'], pesan['chat_id'], "transaksi dengan nomor rekening anda atau tahun dan bulan tidak sesuai dan tidak ada"))
                con_bank.commit()

                c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                               (pesan['id'], pesan['chat_id']))
                con_bank.commit()

        elif newCommand[0][0] == 'transfer' or newCommand[0][0] == 'trf' and pesan['picture_id'] is ' ':
            c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                         (pesan['id'], pesan['chat_id'], 'Silahkan Upload Bukti Pembayaran Anda'))
            con_bank.commit()

            c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                         (pesan['id'], pesan['chat_id']))
            con_bank.commit()
        else:
            c_bank.execute("select * from commands where `command` = '%s'"%newCommand[0][0])
            replay = c_bank.fetchone()

            c_bank.execute("insert into outboxes(inbox_id,chat_id,message) values('%s','%s','%s')" %
                         (pesan['id'], pesan['chat_id'], replay['messages']))
            con_bank.commit()

            c_bank.execute("UPDATE inboxes set flag = 'processed' where id='%s' and chat_id='%s'" %
                         (pesan['id'], pesan['chat_id']))
            con_bank.commit()


def main():
    while True:
        enginePesan()
        time.sleep(1)
if __name__ == "__main__":
    main()