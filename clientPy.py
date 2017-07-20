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

print 'Connected to remote host \n'

# Get user pseudo
pseudo = raw_input('Enter a user name: \n')
s.send(pseudo)

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
                sys.stdout.write('\r' + data + '\n');sys.stdout.flush()
                sys.stdout.write('>>> ' )
                sys.stdout.flush()
        else :
            # user entered a message
            msg = sys.stdin.readline()
            s.send(msg)
            sys.stdout.write('>>> ' )
            sys.stdout.flush()


if __name__ == "__main__":
    sys.exit(chat_client())
