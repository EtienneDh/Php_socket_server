import sys
import socket
import select

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
# resetInput(True)

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
                # sys.stdout.flush()
                sys.stdout.write('\n' + '[Other]: ' + data + '\n');sys.stdout.flush()
                sys.stdout.write('>>> ' )
                sys.stdout.flush()

        else :
            # user entered a message
            msg = sys.stdin.readline()
            s.send(msg)

            sys.stdout.write('[Me]: ' + msg)

            sys.stdout.write('>>> ' )
            sys.stdout.flush()
            # sys.stdout.write('[Me] '); sys.stdout.flush()



if __name__ == "__main__":
    sys.exit(chat_client())
