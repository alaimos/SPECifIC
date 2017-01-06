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
require.packages(c("getopt"))
source(paste0(script.dir, "/common.R"))
load(paste0(script.dir, "/../../data/enrichment_data.RData"))

cmd.line.valid.args <- matrix(c(
    "list",               "l",  1,  "character",  "A file containing a list of genes in a subpathway",
    "maxpv",              "p",  2,  "double",     "Maximal p-value threshold",
    "pvadjust",           "a",  2,  "character",  "A pvalue adjustment method",
    "output",             "o",  1,  "character",  "Output",
    "help",               "h",  0,  "logical",    "This help"
), ncol=5, byrow=TRUE)

opt <- getopt(cmd.line.valid.args)

if (!is.null(opt$help)) {
    cat(paste(getopt(cmd.line.valid.args, usage=TRUE), "\n"))
    q(status=100, save="no")
} else {
    tryCatch({
        if (!file.exists(opt$list)) {
            stop("Input list file does not exist")
        }
        if (is.null(opt$pvadjust)) {
            opt$pvadjust <- "BH"
        }
        if (is.null(opt$maxpv)) {
            opt$maxpv <- 1
        }
        lst <- unique(unlist(strsplit(x=readLines(opt$list, warn=FALSE), split=",", fixed=TRUE)))
        lst <- lst[lst != ""]
        if (length(lst) <= 0) {
            stop("Empty input list")
        }
        er  <- compute.enrichment(gs=lst, enrichment.data=enrichment.data, 
                                  total.annotated=total.annotated, 
                                  total.annotated.by.term=total.annotated.by.term,
                                  enrichment.map=enrichment.map, 
                                  p.adjust.method=opt$pvadjust)
        result <- Reduce(rbind, er)
        result <- result[result$p.value.adjusted <= opt$maxpv,]
        write.table(result, file=opt$output, append=FALSE, quote=TRUE, sep="\t", row.names=FALSE,
                    col.names=TRUE)
    }, error=function (e) {
        cat(e$message,"\n")
        q(status=102, save="no")
    })
    q(status=0, save="no")
}