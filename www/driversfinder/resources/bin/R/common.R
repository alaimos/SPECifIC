#!/usr/bin/env Rscript
##########################################################################################################
# Developed by S. Alaimo (alaimos at dmi dot unict dot it)
##########################################################################################################

make.heatmap <- function (case, cntl, selection, output) {
    mat  <- cbind(case, cntl)
    rownames(mat) <- gsub("hsa:","", rownames(mat))
    selection <- intersect(selection, rownames(mat))
    if (length(selection) <= 0) {
        stop("No valid element selected for the heatmap")
    }
    mat <- mat[selection,]
    pheatmap(mat, 
             legend = TRUE, 
             color = colorRampPalette(c("navy", "white", "firebrick3"))(100),
             cluster_row = FALSE,
             cluster_col = FALSE, 
             border_color = "black",
             cellwidth = 20, 
             cellheight = 20, 
             fontsize = 8,
             filename = output)
    if (file.exists("Rplots.pdf")) {
        unlink("Rplots.pdf")
    }
}