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

number = "127413";

println("Creating " , number , "...")
table = readtable(string("output/" , number , "_answers.csv"));
output = plot(
    table,
    x=:answer,
    Geom.bar(position=:dodge),
    Coord.Cartesian(),
    Guide.xlabel("Given answer"),
    Guide.ylabel("# of answers"),
    style
);
draw(PDF(string("graphs/" , number , "_answers.pdf"), 1600px, 800px), output);
