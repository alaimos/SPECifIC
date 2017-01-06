#!/usr/bin/env Rscript
##########################################################################################################
# Developed by S. Alaimo (alaimos at dmi dot unict dot it)
##########################################################################################################
script.dir <- dirname((function() {
    cmdArgs <- commandArgs(trailingOnly = FALSE)
    needle <- "--file="
    match <- grep(needle, cmdArgs)
    if (length(match) > 0) {
        # Rscript
        return(normalizePath(sub(needle, "", cmdArgs[match])))
    } else {
        # 'source'd via R console
        return(normalizePath(sys.frames()[[1]]$ofile))
    }
})())
source(paste0(script.dir, "/setup.R"))
require.packages(c("pheatmap", "getopt","limma"))
source(paste0(script.dir, "/common.R"))

cmd.line.valid.args <- matrix(c(
    "case",               "c",  1,  "character",  "Case",
    "control",            "t",  1,  "character",  "Control",
    "selection",          "s",  1,  "character",  "A selection of genes",
    "output",             "o",  1,  "character",  "Output",
    "help",               "h",  0,  "logical",    "This help"
), ncol=5, byrow=TRUE)

opt <- getopt(cmd.line.valid.args)

if (!is.null(opt$help)) {
    cat(paste(getopt(cmd.line.valid.args, usage=TRUE), "\n"))
    q(status=100, save="no")
} else {
    tryCatch({
        case <- read.delim(file=opt$case,    header=TRUE, sep="\t", dec=".", row.names=1, check.names=FALSE)
        cntl <- read.delim(file=opt$control, header=TRUE, sep="\t", dec=".", row.names=1, check.names=FALSE)
        lst <- unique(unlist(strsplit(x=readLines(opt$selection, warn=FALSE), split=",", fixed=TRUE)))
        lst <- lst[lst != ""]
        if (length(lst) <= 0) {
            stop("Empty input list")
        }
        lst <- lst[1:min(100,length(lst))]
        #selection <- gsub("\\s+", "", unlist(strsplit(opt$selection, split=",", fixed=TRUE, perl=FALSE)))
        make.heatmap(case, cntl, lst, opt$output)
    }, error=function (e) {
        cat(e$message,"\n")
        q(status=102, save="no")
    })
    q(status=0, save="no")
}