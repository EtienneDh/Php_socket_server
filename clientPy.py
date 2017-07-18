import sys
import socket
import select


def resetInput(user = True):
    sys.stdout.write('\n')
    sys.stdout.write('\n')
    sys.stdout.write('\n')
    if user is True:
        sys.stdout.write('[Me] ')
    else:
        sys.stdout.write('[Other] ')
    sys.stdout.flush()
    return

# connect to remote host
host = "127.0.0.1"
port = 5000

s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
s.settimeout(2)

try :
    s.connect((host, port))
except :
    print 'Unable to connect'
    sys.exit()

print 'Connected to remote host. You can start sending messages'


# sys.stdout.write('[Me] '); sys.stdout.flush()
resetInput(True)

while 1:
    socket_list = [sys.stdin, s]

    # Get the list sockets which are readable
    ready_to_read,ready_to_write,in_error = select.select(socket_list , [], [])

    for sock in ready_to_read:
        if sock == s:
            # incoming message from remote server, s
            data = sock.recv(4096)
            if not data :
                print '\nDisconnected from chat server'
                sys.exit()
            else :
                #print data
                sys.stdout.write(data);
                resetInput(False)

        else :
            # user entered a message
            msg = sys.stdin.readline()
            s.send(msg)
            resetInput(True)



if __name__ == "__main__":
    sys.exit(chat_client())
