##########################################################################################################
# Developed by S. Alaimo (alaimos at dmi dot unict dot it)
##########################################################################################################
require.packages <- function (packages) {
    not.installed <- setdiff(packages, rownames(installed.packages()))
    if (length(not.installed) > 0) {
        #suppressMessages(suppressWarnings(try({
            library(BiocInstaller)
            biocLite(not.installed, dependencies=TRUE)
        #}, silent=TRUE)))
    }
    not.installed <- setdiff(packages, rownames(installed.packages()))
    if (length(not.installed) > 0) {
        stop(paste0("Unable to install required packages: ", paste(not.installed, collapse=", "))) 
    }
    for (p in packages) {
        suppressMessages(
            suppressPackageStartupMessages(
                library(package=p, character.only=TRUE, quietly=TRUE, verbose=FALSE)))
    }
}
