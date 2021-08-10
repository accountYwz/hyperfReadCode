import sys

argvLen = len(sys.argv)

if (argvLen < 2) :
    print("没有传入镜像名称")
    sys.exit(1)

imageName = sys.argv[1]

filename = "Dockerfile.content"

newContent = ""

with open("./" + filename, "r", encoding="utf-8") as f :
    for line in f:
        if ( "FROM" in line) :
            line = line.replace(line, "FROM " + imageName + "\n")
        newContent += line

with open("./" + filename, "w", encoding="utf-8") as f :
    f.write(newContent)

sys.exit(0)
