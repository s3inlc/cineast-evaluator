Pkg.add("DataFrames")
Pkg.add("Gadfly")
Pkg.add("Cairo")
Pkg.add("Fontconfig")
using DataFrames
using Gadfly
using Cairo
using Fontconfig

# Plot all validities

println("All Validities...")
table = readtable("output/allValidities.csv");
output = plot(table,x=:validity,Geom.histogram(bincount=50),Scale.y_log2,Coord.Cartesian(xmin=0, xmax=1, ymin=0),Guide.xlabel("Validities"),Guide.ylabel("# of Sessions"));
draw(PDF("graphs/allValidities.pdf", 800px, 400px), output);

println("Anonymous Validities...")
table=readtable("output/anonymousValidities.csv")
output = plot(table,x=:validity,Geom.histogram(bincount=50),Scale.y_log2,Coord.Cartesian(xmin=0, xmax=1, ymin=0),Guide.xlabel("Validities"),Guide.ylabel("# of Sessions"))
draw(PDF("graphs/anonymousValidities.pdf", 800px, 400px), output);

println("Microworker Validities...")
table=readtable("output/microworkerValidities.csv")
output = plot(table,x=:validity,Geom.histogram(bincount=50),Scale.y_log2,Coord.Cartesian(xmin=0, xmax=1, ymin=0),Guide.xlabel("Validities"),Guide.ylabel("# of Sessions"))
draw(PDF("graphs/microworkerValidities.pdf", 800px, 400px), output);

println("Player Validities...")
table=readtable("output/playerValidities.csv")
output = plot(table,x=:validity,Geom.histogram(bincount=50),Scale.y_log2,Coord.Cartesian(xmin=0, xmax=1, ymin=0),Guide.xlabel("Validities"),Guide.ylabel("# of Sessions"))
draw(PDF("graphs/playerValidities.pdf", 800px, 400px), output);

println("Played games...")
table=readtable("output/gamesPlayed.csv")
output = plot(table,x=:gamesPlayed,Geom.histogram(),Coord.Cartesian(xmax=350))
draw(PDF("graphs/gamesPlayed.pdf", 800px, 400px), output);

println("Number of days playing...")
table = readtable("output/daysOfPlaying.csv")
output = plot(table,x=:days,Geom.histogram(),Coord.Cartesian(ymin=0))
draw(PDF("graphs/daysOfPlaying.pdf", 800px, 400px), output);

println("Games per day...")
table=readtable("output/gamesPerDay.csv")
output = plot(table,x=:games,Geom.histogram())
draw(PDF("graphs/gamesPerDay.pdf", 800px, 400px), output);

println("Games per day overall...")
table=readtable("output/gamesPerDayOverall.csv")
output = plot(table,x=:games,Geom.histogram())
draw(PDF("graphs/gamesPerDayOverall.pdf", 800px, 400px), output);
