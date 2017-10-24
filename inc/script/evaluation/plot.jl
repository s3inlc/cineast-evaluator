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
labels = ["0", "200", "400", "600", "800", "1000"];
yticks=log2.([1,10,100,1000]);
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Coord.Cartesian(xmin=0, xmax=1, ymin=0,ymax=11),
    Guide.yticks(ticks=yticks),
    Guide.xlabel("Validity"),
    Guide.ylabel("# of Sessions"),
    style
);
draw(PDF("graphs/allValidities.pdf", 1600px, 800px), output);

println("Anonymous Validities...")
table=readtable("output/anonymousValidities.csv")
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Coord.Cartesian(xmin=0, xmax=1, ymin=0,ymax=11),
    Guide.yticks(ticks=yticks),
    Guide.xlabel("Validity"),
    Guide.ylabel("# of Sessions"),
    #Guide.title("Validities of anonymous users"),
    Theme(
        major_label_font_size=35pt,
        minor_label_font_size=30pt,
        background_color="white",
        major_label_font="CMU Serif",
        minor_label_font="CMU Serif",
        default_color="red"
    )
);
draw(PDF("graphs/anonymousValidities.pdf", 1600px, 800px), output);

println("Microworker Validities...")
table = readtable("output/microworkerValidities.csv")
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Coord.Cartesian(xmin=0, xmax=1, ymin=0,ymax=11),
    Guide.yticks(ticks=yticks),
    Guide.xlabel("Validity"),
    Guide.ylabel("# of Sessions"),
    #Guide.title("Validities of Microworkers"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="green"
        )
);
draw(PDF("graphs/microworkerValidities.pdf", 1600px, 800px), output);

println("Player Validities...")
table = readtable("output/playerValidities.csv")
output = plot(
    table,
    x=:validity,
    Geom.histogram(bincount=50),
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Coord.Cartesian(xmin=0, xmax=1, ymin=0,ymax=11),
    Guide.yticks(ticks=yticks),
    Guide.xlabel("Validity"),
    Guide.ylabel("# of Sessions"),
    #Guide.title("Validities of Players"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="orange"
        )
);
draw(PDF("graphs/playerValidities.pdf", 1600px, 800px), output);


println("All Answers...")
table = readtable("output/allAnswers.csv")
output = plot(
    table,
    x=:answer,
    y=:count,
    Guide.yticks(ticks=[0;50000;100000;150000]),
    Scale.y_continuous(labels=d-> @sprintf("%d",d/10000)),
    Geom.bar(position=:dodge),
    Coord.Cartesian(ymax=150000),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of answers x10'000"),
    Theme(
        major_label_font_size=35pt,
        minor_label_font_size=30pt,
        background_color="white",
        major_label_font="CMU Serif",
        minor_label_font="CMU Serif",
        bar_spacing=150px
    )
);
draw(PDF("graphs/allAnswers.pdf", 1600px, 800px), output);

println("Microworkers Answers...")
table = readtable("output/microworkerAnswers.csv")
output = plot(
    table,
    x=:answer,
    y=:count,
    Guide.yticks(ticks=[0;50000;100000;150000]),
    Scale.y_continuous(labels=d-> @sprintf("%d",d/10000)),
    Geom.bar(position=:dodge),
    Coord.Cartesian(ymax=150000),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of answers x10'000"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="green",
            bar_spacing=150px
        )
);
draw(PDF("graphs/microworkerAnswers.pdf", 1600px, 800px), output);

println("Player Answers...")
table = readtable("output/playerAnswers.csv")
output = plot(
    table,
    x=:answer,
    y=:count,
    Guide.yticks(ticks=[0;50000;100000;150000]),
    Scale.y_continuous(labels=d-> @sprintf("%d",d/10000)),
    Geom.bar(position=:dodge),
    Coord.Cartesian(ymax=150000),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of answers x10'000"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="orange",
            bar_spacing=150px
        )
);
draw(PDF("graphs/playerAnswers.pdf", 1600px, 800px), output);

println("Anonymous Answers...")
table = readtable("output/anonymousAnswers.csv")
output = plot(
    table,
    x=:answer,
    y=:count,
    Guide.yticks(ticks=[0;50000;100000;150000]),
    Scale.y_continuous(labels=d-> @sprintf("%d",d/10000)),
    Geom.bar(position=:dodge),
    Coord.Cartesian(ymax=150000),
    Guide.xlabel("Answer"),
    Guide.ylabel("# of answers x10'000"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="red",
            bar_spacing=150px
        )
);
draw(PDF("graphs/anonymousAnswers.pdf", 1600px, 800px), output);

labels = ["0","10 min","20 min","30 min","40 min", "50 min"];
xticks = [0,600,1200,1800,2400,3000];

println("All Durations...")
table = readtable("output/allDurations.csv")
output = plot(
    table,
    x=:duration,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=3100, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/600)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    style
);
draw(PDF("graphs/allDurations.pdf", 1600px, 800px), output);

println("Microworker Durations...")
table = readtable("output/microworkerDurations.csv")
output = plot(
    table,
    x=:duration,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=3100, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/600)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="green"
        )
);
draw(PDF("graphs/microworkerDurations.pdf", 1600px, 800px), output);

println("Player Durations...")
table = readtable("output/playerDurations.csv")
output = plot(
    table,
    x=:duration,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=3100, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/600)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="orange"
        )
);
draw(PDF("graphs/playerDurations.pdf", 1600px, 800px), output);

println("Anonymous Durations...")
table = readtable("output/anonymousDurations.csv")
output = plot(
    table,
    x=:duration,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=3100, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/600)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="red"
        )
);
draw(PDF("graphs/anonymousDurations.pdf", 1600px, 800px), output);

labels = ["0","0.5 min","1 min","1.5 min","2 min"];
xticks = [0,30,60,90,120];

println("All Durations Normalized...")
table = readtable("output/allDurationsNormalized.csv")
output = plot(
    table,
    x=:durationNormalized,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=125, ymin=0,ymax=12),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/30)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    style
);
draw(PDF("graphs/allDurationsNormalized.pdf", 1600px, 800px), output);

println("Microworker Durations Normalized...")
table = readtable("output/microworkerDurationsNormalized.csv")
output = plot(
    table,
    x=:durationNormalized,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=125, ymin=0,ymax=12),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/30)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="green"
        )
);
draw(PDF("graphs/microworkerDurationsNormalized.pdf", 1600px, 800px), output);

println("Player Durations Normalized...")
table = readtable("output/playerDurationsNormalized.csv")
output = plot(
    table,
    x=:durationNormalized,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=125, ymin=0,ymax=12),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/30)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="orange"
        )
);
draw(PDF("graphs/playerDurationsNormalized.pdf", 1600px, 800px), output);

println("Anonymous Durations Normalized...")
table = readtable("output/anonymousDurationsNormalized.csv")
output = plot(
    table,
    x=:durationNormalized,
    Scale.y_log2(labels=d-> @sprintf("%d",2^d)),
    Geom.histogram(),
    Coord.Cartesian(xmin=0, xmax=125, ymin=0,ymax=12),
    Guide.yticks(ticks=yticks),
    Scale.x_continuous(labels = x -> get(labels, round(Int, x/30)+1, "")),
    Guide.xticks(ticks=xticks),
    Guide.xlabel("Time"),
    Guide.ylabel("# of Sessions"),
    Theme(
            major_label_font_size=35pt,
            minor_label_font_size=30pt,
            background_color="white",
            major_label_font="CMU Serif",
            minor_label_font="CMU Serif",
            default_color="red"
        )
);
draw(PDF("graphs/anonymousDurationsNormalized.pdf", 1600px, 800px), output);



yticks=[0,1,2,3,4,5,6,7,8,9,10];

println("Unique workers batch 1...")
table = readtable("output/Batch_2823797_batch_resultsWorkers.csv")
output = plot(
    table,
    x=:count,
    Scale.y_sqrt(labels=d-> @sprintf("%d",d^2)),
    Coord.Cartesian(xmin=0, xmax=90, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Geom.histogram(),
    Guide.xlabel("# of HITs"),
    Guide.ylabel("# of Workers"),
    style
);
draw(PDF("graphs/Batch-2823797-batch-resultsWorkers.pdf", 1600px, 800px), output);

println("Unique workers batch 2...")
table = readtable("output/Batch_2823956_batch_resultsWorkers.csv")
output = plot(
    table,
    x=:count,
    Scale.y_sqrt(labels=d-> @sprintf("%d",d^2)),
    Coord.Cartesian(xmin=0, xmax=90, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Geom.histogram(),
    Guide.xlabel("# of HITs"),
    Guide.ylabel("# of Workers"),
    style
);
draw(PDF("graphs/Batch-2823956-batch-resultsWorkers.pdf", 1600px, 800px), output);

println("Unique workers batch 3...")
table = readtable("output/Batch_2848581_batch_resultsWorkers.csv")
output = plot(
    table,
    x=:count,
    Scale.y_sqrt(labels=d-> @sprintf("%d",d^2)),
    Coord.Cartesian(xmin=0, xmax=90, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Geom.histogram(),
    Guide.xlabel("# of HITs"),
    Guide.ylabel("# of Workers"),
    style
);
draw(PDF("graphs/Batch-2848581-batch-resultsWorkers.pdf", 1600px, 800px), output);

println("Unique workers batch 4...")
table = readtable("output/Batch_2853164_batch_resultsWorkers.csv")
output = plot(
    table,
    x=:count,
    Scale.y_sqrt(labels=d-> @sprintf("%d",d^2)),
    Coord.Cartesian(xmin=0, xmax=90, ymin=0,ymax=10),
    Guide.yticks(ticks=yticks),
    Geom.histogram(),
    Guide.xlabel("# of HITs"),
    Guide.ylabel("# of Workers"),
    style
);
draw(PDF("graphs/Batch-2853164-batch-resultsWorkers.pdf", 1600px, 800px), output);

yticks=[0,2,4,6,8,10,12,14,16];

println("Unique workers of all batches...")
table = readtable("output/allWorkers.csv")
output = plot(
    table,
    x=:count,
    Scale.y_sqrt(labels=d-> @sprintf("%d",d^2)),
    Coord.Cartesian(xmin=0, xmax=90, ymin=0,ymax=16),
    Guide.yticks(ticks=yticks),
    Geom.histogram(),
    Guide.xlabel("# of HITs"),
    Guide.ylabel("# of Workers"),
    style
);
draw(PDF("graphs/allWorkers.pdf", 1600px, 800px), output);




println("Played games...")
table = readtable("output/gamesPlayed.csv")
output = plot(
    table,
    x=:gamesPlayed,
    Geom.histogram(),
    Coord.Cartesian(xmax=320),
    Guide.xlabel("# of players"),
    Guide.ylabel("# of games played"),
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
    Guide.xlabel("days"),
    Guide.ylabel("# of players"),
    style
);
draw(PDF("graphs/daysOfPlaying.pdf", 1600px, 800px), output);

println("Games per day...")
table = readtable("output/gamesPerDay.csv")
output = plot(
    table,
    x=:games,
    Geom.histogram(),
    Guide.xlabel("Games per day"),
    Guide.ylabel("# of players"),
    style
);
draw(PDF("graphs/gamesPerDay.pdf", 1600px, 800px), output);

println("Games per day overall...")
table = readtable("output/gamesPerDayOverall.csv")
output = plot(
    table,
    x=:games,
    Geom.histogram(),
    Guide.xlabel("Games per day"),
    Guide.ylabel("# of players"),
    style
);
draw(PDF("graphs/gamesPerDayOverall.pdf", 1600px, 800px), output);



println("Random Validities...")
table = readtable("output/randomValidities.csv")
output = plot(
    table,
    x=:validity,
    Geom.histogram(),
    Guide.xlabel("Validity"),
    Guide.ylabel("# of sessions"),
    style
);
draw(PDF("graphs/randomValidities.pdf", 1600px, 800px), output);
