import pymysql
import telepot
import time

BOT = telepot.Bot('573076952:AAGd8uvyRiPoF-UhjNDY4_DdO57bfDL0Eoo')
database = {
    "host": "localhost",
    "port": 3306,
    "user": "root",
    "password": "",
    "db": "ims_1",
    "cursorclass": pymysql.cursors.DictCursor
}

def sendMessage(chat_id,message,update_id,photo_id):
    con_db = pymysql.connect(**database)
    c_db = con_db.cursor()
    pesan = message.split(' ')[0]
    value = ' '
    newBulan = ''
    newYear = ''
    bulan = ['april','mei','maret','juni','juli','november','september','oktober','densember', 'agustus', 'januari','februari']
    tahun = []
    for a in range(1, 100):
        a = 2000 + a
        year = str(a)
        tahun.append(year)

    if pesan.lower() in ['trans','transfer','trf']:
       newMessage = pesan
       value = message.split(' ')[1]
       for word in message.lower().split(" "):
           if word in bulan:
               print(word)
               if word == 'januari':
                   newBulan = '1'
               elif word == 'februari':
                   newBulan = '2'
               elif word == 'maret':
                   newBulan = '3'
               elif word == 'april':
                   newBulan = '4'
               elif word == 'mei':
                   newBulan = '5'
               elif word == 'juni':
                   newBulan = '6'
               elif word == 'juli':
                   newBulan = '7'
               elif word == 'agustus':
                   newBulan = '8'
               elif word == 'september':
                   newBulan = '9'
               elif word == 'oktober':
                   newBulan = '10'
               elif word == 'november':
                   newBulan = '11'
               else:
                   newBulan = '12'
       for word in message.lower().split(" "):
           if word in tahun:
               newYear=word
    else:
        newMessage = message

    c_db.execute("insert inboxes(chat_id,update_id,messages,picture_id,pdam_number,bulan,tahun) values(%i,'%s','%s','%s','%s','%s','%s')" %
                 (chat_id,update_id,newMessage,photo_id,value,newBulan,newYear))
    con_db.commit()

    c_db.execute("SELECT LAST_INSERT_ID()")
    id = c_db.fetchone()
    return id['LAST_INSERT_ID()']

def receiveMessage(id):
    con_db = pymysql.connect(**database)
    c_db = con_db.cursor()

    c_db.execute("SELECT * FROM `inboxes` WHERE `id`='%s'" % (id))
    inbox = c_db.fetchone()
    if inbox is not None:
        if (inbox['flag'] == 'processed'):
            c_db.execute("SELECT * FROM `outboxes` WHERE `inbox_id`=%s" % (id))
            outbox = c_db.fetchall()
            for msg in outbox:
                print(msg)
                BOT.sendMessage(msg['chat_id'],msg['message'])

                c_db.execute("UPDATE `outboxes` SET flag = 'sent', sent_time = NOW() WHERE `inbox_id`='%s'" % (inbox['id']))
                con_db.commit()

                c_db.execute("UPDATE `inboxes` SET flag = 'done' WHERE `id`='%s'" % (id))
                con_db.commit()
            return True
        elif (inbox['flag'] == 'done'):
            return True
        else:
            return False


def main():
    received = True
    id = ''
    update_id = ' '
    newUpdateId = ''
    con_db = pymysql.connect(**database)
    c_db = con_db.cursor()
    while True:
        # msgs = BOT.getUpdates()
        # print(msgs[0]['message']['photo'])
        # p = BOT.getFile('AgADBQADOKgxG-EC-FfnR6bFUXfdH52z0zIABN5gtezwNULJS14DAAEC')
        # print(p)
        # BOT.download_file('AgADBQADOKgxG-EC-FfnR6bFUXfdH52z0zIABN5gtezwNULJS14DAAEC','c:/xampp/file.jpg')
        if(received):
                msgs = BOT.getUpdates(update_id)
                for msg in msgs:
                    update_id = msg['update_id']
                print(msgs)
                if msgs != []:
                    c_db.execute("SELECT * FROM `inboxes` WHERE `chat_id`=%s order by `id` desc " % (msgs[0]['message']['chat']['id']))
                    inbox = c_db.fetchone()
                    if (inbox is None):
                        print('if 1')
                        if (newUpdateId == update_id ):
                            message = ''
                            photo = ' '
                            if 'text' not in msgs[0]['message']:
                                message = msgs[0]['message']['caption']
                            else:
                                message = msgs[0]['message']['text']

                            if 'photo' not in msgs[0]['message']:
                                photo = ' '
                            else:
                                photo = msgs[0]['message']['photo'][2]['file_id']

                            id = sendMessage(msgs[0]['message']['chat']['id'], message, msgs[0]['update_id'],
                                             photo)
                            received = False
                        else:
                            newUpdateId = update_id
                    else:
                        print('if 2')
                        if (newUpdateId == update_id and inbox['update_id'] != update_id):
                            message = ''
                            photo = ' '
                            if 'text' not in msgs[0]['message']:
                                message = msgs[0]['message']['caption']
                            else:
                                message = msgs[0]['message']['text']

                            if 'photo' not in msgs[0]['message']:
                                photo = ' '
                            else:
                                photo = msgs[0]['message']['photo'][2]['file_id']

                            id = sendMessage(msgs[0]['message']['chat']['id'], message, msgs[0]['update_id'],
                                             photo)
                            received = False
                        else:
                            print('else')
                            newUpdateId = update_id
                    con_db.rollback()
        else:
            received = receiveMessage(id)
        time.sleep(1)

if __name__ == "__main__":
    main()