Pkg.add("DataFrames")
Pkg.add("Gadfly")
Pkg.add("Cairo")
Pkg.add("Fontconfig")
using DataFrames
using Gadfly
using Cairo
using Fontconfig

# Plot a Gaussian curve

style = Theme(
    major_label_font_size=35pt,
    minor_label_font_size=30pt,
    background_color="white",
    major_label_font="CMU Serif",
    minor_label_font="CMU Serif",
    line_width=5px
)

mus = [0.807033, 1.1918, 0.667843, 2.32305, 2.0586];
sigmas = [0.416768, 1.15322, 1.22268, 0.458837, 0.896344];
numbers = ["377", "11177", "127413", "140054", "7506"];

function getGaussian(sigma, mu, pos)
    expo = ((-1./2.0) .* (((pos-mu)./sigma).^2));
    return (1./(sigma.*sqrt(2.*3.14159265359))).*exp(expo);
end

for i = 1:5
    println("Creating Gaussian " , numbers[i] , "...");
    mu = mus[i];
    sigma = sigmas[i];

    x1=collect(0:.01:3);
    labels = ["                 Not Similar", "Slightly Similar", "Very Similar", "Nearly Identical                        "];

    output = plot(
        x=x1,
        y=getGaussian(sigma, mu, x1),
        Geom.line,
        Scale.x_continuous(labels = x -> get(labels, round(Int, x)+1, "")),
        Guide.xlabel("Answer"),
        Guide.ylabel("Gaussian"),
        Coord.cartesian(xmin=0),
        style
    );
    draw(PDF(string("graphs/" , numbers[i] , "-gaussian.pdf"), 1600px, 800px), output);
end
