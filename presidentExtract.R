# This script extracts the Year, Candidates, Electoral Votes, Popular Votes, and VPs for each election.
# It also contains information on which # president including non-election year presidents (aka president before died)

library(rvest)
print("Starting read")
url <- "http://2012election.procon.org/view.resource.php?resourceID=004332"
webpage <- read_html(url)
p_table <- html_nodes(webpage, 'table')
p <- p_table[[20]]
g <- html_table(p, fill=TRUE, trim=TRUE)
names(g) <- c("Year", "Candidates", "Parties", "Electoral Votes", "Popular Votes", "VPs")
g <- g[(1:6)] # Get rid of 4 extraneous columns
g <- g[grepl("\\([[:digit:]]", g$Candidates),] # Get rows of candidates with open parenthesis followed by number