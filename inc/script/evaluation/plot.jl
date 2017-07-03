Pkg.add("DataFrames")
Pkg.add("Gadfly")
Pkg.add("Cairo")
Pkg.add("Fontconfig")
using DataFrames
using Gadfly
using Cairo
using Fontconfig

# Plot all validities

style = Theme(
    major_label_font_size=35pt,
    minor_label_font_size=30pt,
    background_color="white",
    major_label_font="CMU Serif",
    minor_label_font="CMU Serif"
)

println("All Validities...")
table = readtable("output/allValidities.csv");
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2,
    Coord.Cartesian(xmin=0, xmax=1, ymin=0),
    Guide.xlabel("Validities"),
    Guide.ylabel("# of Sessions"),
    #Guide.title("Validities of all Sessions"),
    style
);
draw(PDF("graphs/allValidities.pdf", 1600px, 800px), output);

println("Anonymous Validities...")
table=readtable("output/anonymousValidities.csv")
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2,
    Coord.Cartesian(xmin=0, xmax=1, ymin=0),
    Guide.xlabel("Validities"),
    Guide.ylabel("# of Sessions"),
    #Guide.title("Validities of anonymous users"),
    style
);
draw(PDF("graphs/anonymousValidities.pdf", 1600px, 800px), output);

println("Microworker Validities...")
table = readtable("output/microworkerValidities.csv")
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2,
    Coord.Cartesian(xmin=0, xmax=1, ymin=0),
    Guide.xlabel("Validities"),
    Guide.ylabel("# of Sessions"),
    #Guide.title("Validities of Microworkers"),
    style
);
draw(PDF("graphs/microworkerValidities.pdf", 1600px, 800px), output);

println("Player Validities...")
table = readtable("output/playerValidities.csv")
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2,
    Coord.Cartesian(xmin=0, xmax=1, ymin=0),
    Guide.xlabel("Validities"),
    Guide.ylabel("# of Sessions"),
    #Guide.title("Validities of Players"),
    style
);
draw(PDF("graphs/playerValidities.pdf", 1600px, 800px), output);


println("All Answers...")
table = readtable("output/allAnswers.csv")
output = plot(
    table,
    x=:answer,
    Geom.histogram(),
    Coord.Cartesian(ymin=0),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of Answers"),
    style
);
draw(PDF("graphs/allAnswers.pdf", 1600px, 800px), output);

println("Microworkers Answers...")
table = readtable("output/microworkerAnswers.csv")
output = plot(
    table,
    x=:answer,
    Geom.histogram(),
    Coord.Cartesian(ymin=0),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of Answers"),
    style
);
draw(PDF("graphs/microworkerAnswers.pdf", 1600px, 800px), output);

println("Player Answers...")
table = readtable("output/playerAnswers.csv")
output = plot(
    table,
    x=:answer,
    Geom.histogram(),
    Coord.Cartesian(ymin=0),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of Answers"),
    style
);
draw(PDF("graphs/playerAnswers.pdf", 1600px, 800px), output);

println("Anonymous Answers...")
table = readtable("output/anonymousAnswers.csv")
output = plot(
    table,
    x=:answer,
    Geom.histogram(),
    Coord.Cartesian(ymin=0),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of Answers"),
    style
);
draw(PDF("graphs/anonymousAnswers.pdf", 1600px, 800px), output);





println("Played games...")
table = readtable("output/gamesPlayed.csv")
output = plot(
    table,
    x=:gamesPlayed,
    Geom.histogram(),
    Coord.Cartesian(xmax=350),
    style
);
draw(PDF("graphs/gamesPlayed.pdf", 1600px, 800px), output);

println("Number of days playing...")
table = readtable("output/daysOfPlaying.csv")
output = plot(
    table,
    x=:days,
    Geom.histogram(),
    Coord.Cartesian(ymin=0),
    style
);
draw(PDF("graphs/daysOfPlaying.pdf", 1600px, 800px), output);

println("Games per day...")
table = readtable("output/gamesPerDay.csv")
output = plot(
    table,
    x=:games,
    Geom.histogram(),
    style
);
draw(PDF("graphs/gamesPerDay.pdf", 1600px, 800px), output);

println("Games per day overall...")
table = readtable("output/gamesPerDayOverall.csv")
output = plot(
    table,
    x=:games,
    Geom.histogram(),
    style
);
draw(PDF("graphs/gamesPerDayOverall.pdf", 1600px, 800px), output);
