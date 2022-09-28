from datetime import datetime
import time

timeNow             = datetime.now()
# current_time        = timeNow.strftime("%Y-%m-%d")
current_time        = 1663980923
print(datetime.fromtimestamp(current_time))

# import datetime
# timeStamp           = time.mktime(datetime.datetime.strptime(current_time, "%Y-%m-%d").timetuple())
# print(timeStamp)

a = "445"
print("1233"+ a)