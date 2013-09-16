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

    [username, problem, path] = row
    type = path.split(".")[-1]
    filename = "Prob%s.%s" % (problem, type)

    print "Testing:",path

    newpath = testarea+filename
    shutil.copyfile(path, newpath)

    status = 0

    # C
    if type == "c":
        [status, output] = commands.getstatusoutput("gcc %s" % (newpath))
        print "Compile status: ", status
        print "Execution output:"
        print output

        if status == 0:
            [status, output] = commands.getstatusoutput("./%sa.out < %sProb%s/in > %smy.out" % (testarea, testarea, problem, testarea))
            print "Execution status: ", status

    # C++
    if type == "cpp":
        [status, output] = commands.getstatusoutput("g++ %s" % (newpath))
        print "Compile status: ", status
        print "Execution output:"
        print output

        if status == 0:
            [status, output] = commands.getstatusoutput("./%sa.out < %sProb%s/in > %smy.out" % (testarea, testarea, problem, testarea))
            print "Execution status: ", status

    # Java
    if type == "java":
        classpath = newpath.replace(".java","")

        [status, output] = commands.getstatusoutput("javac %s" % (newpath))
        print "Compile status: ", status
        print "Execution output: "
        print output

        if status == 0:
            [status, output] = commands.getstatusoutput("java %s < %sProb%s/in > %smy.out" % (classpath, testarea, problem, testarea))
            print "Execution status: ", status

    # Lua
    if type == "lua":
        [status, output] = commands.getstatusoutput("lua %s < %sProb%s/in > %smy.out" % (newpath, testarea, problem, testarea))
        print "Compile and execute status: ", status

    # Python 2 (not 3)
    if type == "py":
        [status, output] = commands.getstatusoutput("python %s < %sProb%s/in > %smy.out" % (newpath, testarea, problem, testarea))
        print "Compile and execute status: ", status

    # Ruby
    if type == "rb":
        [status, output] = commands.getstatusoutput("ruby %s < %sProb%s/in > %smy.out" % (newpath, testarea, problem, testarea))
        print "Compile and execute status: ", status

    # Haskell
    if type == "hs":
        print "Haskell isn't on Turing."

    # Scala 
    if type == "scala":
        print "Scala isn't on Turing."

    ###############################################################
    ############# The program is compiled and execute. ############
    ###############################################################

    if status == 0:
        [status, output] = commands.getstatusoutput("diff -w %smy.out %sProb%s/out" % (testarea, testarea, problem))
        print "Diff ouput:"
        print output
    else:
        print "Error in either compiling or execution."

    break

if alltested == True:
    print "Nothing to test."
