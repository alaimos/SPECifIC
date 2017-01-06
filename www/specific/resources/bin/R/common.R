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
    mat <- voom(mat)$E
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

compute.pvalue <- function (gs, terms, total, total.by.terms, terms.map=NULL, p.adjust.method="BH") {
    annot <- intersect(gs, names(terms))
    if (length(annot) <= 0) {
        return (NULL)
    }
    selected.terms <- unique(unname(unlist(terms[annot])))
    N <- total
    n <- length(annot)
    M <- total.by.terms[selected.terms]
    k <- numeric(length(selected.terms))
    names(k) <- selected.terms
    for (g in annot) {
        k[terms[[g]]] <- k[terms[[g]]] + 1
    }
    p <- numeric(length(selected.terms))
    names(p) <- selected.terms
    for (t in selected.terms) {
        #p[t] <- 1 - phyper(q=k[t],m=M[t],n=N-M[t],k=n)
        p[t] <- phyper(q=k[t],m=M[t],n=N-M[t],k=n, lower.tail = FALSE)
    }
    selected.terms.name <- selected.terms
    if (!all(is.null(terms.map))) {
        selected.terms.name <- terms.map[selected.terms]
    }
    rho <- M/N
    result <- data.frame(
        term.id=selected.terms,
        term.name=selected.terms.name,
        term.count=k,
        term.expected=n*rho,
        term.var=((n*rho*(1-rho)*(N-n))/(N-1)),
        p.value=p,
        p.value.adjusted=p.adjust(p, method=p.adjust.method),
        ts.M=M,
        ts.N=N,
        ts.n=n
    )
    result <- result[order(result$p.value.adjusted),]
    return (result)
}

compute.enrichment <- function (gs, enrichment.data, total.annotated, total.annotated.by.term, 
                                enrichment.map, p.adjust.method="BH", prev="") {
    result <- vector("list", length(0))
    #names(result) <- names(enrichment.data)
    for (n in names(enrichment.data)) {
        nxt <- ifelse(prev=="",n,paste0(prev,".",n))
        if (is.list(enrichment.data[[n]][[1]])) {
            result <- c(result, compute.enrichment(gs=gs, 
                                                   enrichment.data=enrichment.data[[n]], 
                                                   total.annotated=total.annotated[[n]], 
                                                   total.annotated.by.term=total.annotated.by.term[[n]], 
                                                   enrichment.map=enrichment.map[[n]], 
                                                   p.adjust.method=p.adjust.method, prev=nxt))
        } else {
            tmp <- compute.pvalue(gs=gs, 
                                  terms=enrichment.data[[n]], 
                                  total=total.annotated[[n]], 
                                  total.by.terms=total.annotated.by.term[[n]], 
                                  terms.map=enrichment.map[[n]], 
                                  p.adjust.method=p.adjust.method)
            if (!all(is.null(tmp))) {
                tmp$enrichment.type <- nxt
                result <- c(result, list(tmp))
            }
        }
    }
    return (result)
}



