import sqlite3
import shutil
import commands

alltested = True 
testarea = "../../mytestingarea/"
db = sqlite3.connect('competition.db')

cur = db.cursor()
cur.execute("SELECT contestant, problem, path FROM submissions WHERE status=-1 ORDER BY submitTime")
for row in cur.fetchall():
    alltested = False 
    

    print "Testing:",path

    [username, problem, path] = row
    filename = path.split("/")[-1]
    type = path.split(".")[-1]

    newpath = testarea+filename
    shutil.copyfile(path, newpath)

    status = 0

    if type == "c":
        pass

    if type == "cpp":
        pass

    if type == "java":
        classpath = newpath.replace(".java","")

        [status, output] = commands.getstatusoutput("javac %s" % (newpath))
        print "Compile status: ", status
        print "Execution output: ", output

        if status == 0:
            [status, output] = commands.getstatusoutput("java %s < %sProb%s/in > %smy.out" % (classpath, testarea, problem, testarea))
            print "Execution status: ", status

    if type == "lua":
        pass

    if type == "scala":
        pass

    if type == "py":
        [status, output] = commands.getstatusoutput("python %s < %sProb%s/in > %smy.out" % (newpath, testarea, problem, testarea))
        print "Compile and execute status: ", status

    if type == "hs":
        pass

    if status == 0:
        [status, output] = commands.getstatusoutput("diff %smy.out %sProb%s/out" % (testarea, testarea, problem))
        print "Diff ouput: ", output
    else:
        print "Error in either compiling or execution."

    break

if alltested == True:
    print "Nothing to test."
