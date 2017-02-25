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

compute.pvalue <- function (gs, terms, total, total.by.terms, terms.map=NULL, 
                            p.adjust.method="BH", fraction = 1/3) {
    annot <- intersect(gs, names(terms))
    if (length(annot) <= 0) {
        return (NULL)
    }
    selected.terms <- unique(unname(unlist(terms[annot])))
    k <- table(Reduce(c, terms[annot]))[selected.terms]
    N <- total
    n <- length(annot)
    M <- total.by.terms[selected.terms]
    p <- numeric(length(selected.terms))
    names(p) <- selected.terms
    for (t in selected.terms) {
        p[t] <- phyper(q=k[t],m=M[t],n=N-M[t],k=n, lower.tail = FALSE)
    }
    selected.terms.name <- selected.terms
    if (!is.null(terms.map)) {
        selected.terms.name <- terms.map[selected.terms]
    }
    if (!is.null(fraction) && fraction > 0 && fraction <= 1) {
        p[as.numeric(k) <= round(n*fraction)] <- 1.0
    } else {
        tmp <- numeric(length(selected.terms))
        names(tmp) <- selected.terms
        for (t in selected.terms) {
            tmp[t] <- sum(1:length(gs) * dhyper(x = 1:length(gs), m=total.by.terms[t], 
                                                n=(total-total.by.terms[t]), k=length(gs), 
                                                log = FALSE))
        }
        p[as.numeric(k) <= tmp] <- 1.0
    }
    rho <- M/N
    result <- data.frame(
        term.id=selected.terms,
        term.name=selected.terms.name,
        term.count=as.numeric(k),
        term.expected=n*rho,
        term.var=((n*rho*(1-rho)*(N-n))/(N-1)),
        p.value=p,
        p.value.adjusted=p.adjust(p, method=p.adjust.method),
        ts.M=M,
        ts.N=N,
        ts.n=n,
        stringsAsFactors = FALSE
    )
    result <- result[order(result$p.value.adjusted),]
    return (result)
}

compute.enrichment <- function (gs, enrichment.data, total.annotated, total.annotated.by.term, 
                                enrichment.map, p.adjust.method="BH", prev="", fraction = 1/3) {
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
                                                   p.adjust.method=p.adjust.method, prev=nxt,
                                                   fraction = fraction))
        } else {
            tmp <- compute.pvalue(gs=gs, 
                                  terms=enrichment.data[[n]], 
                                  total=total.annotated[[n]], 
                                  total.by.terms=total.annotated.by.term[[n]], 
                                  terms.map=enrichment.map[[n]], 
                                  p.adjust.method=p.adjust.method,
                                  fraction = fraction)
            if (!all(is.null(tmp))) {
                tmp$enrichment.type <- nxt
                result <- c(result, list(tmp))
            }
        }
    }
    return (result)
}



