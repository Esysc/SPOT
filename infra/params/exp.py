# Import modules needed for script
import pexpect
import getpass
import sys

hostname = str(sys.argv[1])

#username = raw_input('username: ')
password = "***REMOVED***"
# Spawn SSH session to the host
s = pexpect.spawn('telnet %s' %  (hostname))
s.expect('Press any key to continue')
s.send('\r')
# Expect the switch to prompt for a password
s.expect('.*assword: ')
# Send the password
s.sendline(password)
# Send the return key

# Show the running configuration or substitute other commands
s.expect('.*\w+#') #Find the prompt
s.sendline('show running-config')
# Once the terminal window fills, we need to press the spacebar to continue printing the config
nextpage = s.expect(['--.*\w+l-C', '.*\w+#']) 
while nextpage == 0:
  #print everything before came before the continue message
  print s.before
  s.send(' ')
  nextpage = s.expect(['--.*\w+l-C', '.*\w+#'])
# Now we are out of the while loop so we need to print everything after that.
print s.after
s.send('\r')
s.sendline('conf t')
s.expect('.*\w+#')
s.sendline('crypto key generate ssh')

s.expect('.*\w+#')
#Keys can only be uploaded via TFTP, eliminate the next 2 lines if not using SCP for file transfers
s.sendline('ip ssh filetransfer')
s.expect('.*\w+#')
s.sendline('ip ssh')
s.expect('.*\w+#')

