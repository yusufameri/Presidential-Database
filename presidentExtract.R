# This script extracts the Year, Candidates, Electoral Votes, Popular Votes, and VPs for each election.
# It also contains information on which # president including non-election year presidents (aka president before died)

library(rvest)
#url <- "http://2012election.procon.org/view.resource.php?resourceID=004332"
#webpage <- read_html(url)
webpage <- read_html("PresElectHist.html") # Make sure this file is in your working directory!
p_table <- html_nodes(webpage, 'table')
p <- p_table[[18]]
g <- html_table(p, fill=TRUE, trim=TRUE)
names(g) <- c("Year", "Candidates", "Parties", "Electoral Votes", "Popular Votes", "VPs")
g <- g[(1:6)] # Get rid of 4 extraneous columns
g <- g[grepl("\\([[:digit:]]", g$Candidates),] # Get rows of candidates with open parenthesis followed by number

candList<-list() # These lists are used to make a set of the candidates and parties
partyList<-list()

electionFile <- "election.txt" # The election and participated files
participatedFile <- "participated.txt"

# Write initial sql text to file
write("INSERT INTO `election` (`year`, `winner`, `num`) VALUES ", electionFile)
write("INSERT INTO `participated` (`year`, `candidate`, `party`, `electoral_vote`, `popular_vote`, `vice_president`) VALUES ", participatedFile)

elecNum <- 57 # Hard coded value for number of elections

for(i in 1:nrow(g)) {
  row <- g[i,]
  candidates <- strsplit(row$Candidates, "\n")
  parties <- strsplit(row$Parties, "\n")
  electoralVotes <- strsplit(row$`Electoral Votes`, "\n")
  popularVotes <- strsplit(row$`Popular Votes`, "\n")
  vicePresidents <- strsplit(row$VPs, "\n")
  for (j in 1:length(candidates[[1]])) {
    pop <- ""
    vp <- ""
    # Popular votes and vp might not exist
    if (j <= length(popularVotes[[1]])) {
      pop <- (popularVotes[[1]][j])
    } else {
      pop <- ("NULL")
    }
    
    if (j <= length(vicePresidents[[1]])) {
      vp <- (vicePresidents[[1]][j])
    } else {
      vp<- ("NULL")
    }
    
    # Now clean up names to get rid of parenthesis
    cleanCandidate <- strsplit(candidates[[1]][j], " \\(")[[1]][1] # Get rid of end parenthesis
    cleanCandidate <- strsplit(cleanCandidate, "\\*")[[1]][1] # Get rid of trailing asterisks
    cleanCandidate <- trimws(cleanCandidate) # Get rid of leading and trailing whitespace
    cleanCandidate <- gsub("\'", "\\\\\'", cleanCandidate) # Escape ' character for sql
    vp <- strsplit(vp, " \\(")[[1]][1] # Get rid of end parenthesis
    vp <- strsplit(vp, "\\*")[[1]][1] # Get rid of trailing asterisks
    vp <- gsub("\'", "\\\\\'", vp) # Escape ' character for sql
    vp <- trimws(vp)
    
    # Store candidates and parties in hash
    candList[[length(candList) + 1]] <- cleanCandidate
    if (vp != "NULL") {
      candList[[length(candList) + 1]] <- vp # Make sure if no_vp to not add to list
    }
    
    # Clean up parties so ' char is escaped for sql
    party <- parties[[1]][j]
    party <- gsub("\'", "\\\\\'", party)
    partyList[[length(partyList) + 1]] <- party
    
    # Clean up popular votes to get rid of commas in numbers
    pop <- gsub(",", "", pop)
    
    cat(sprintf("%s %s %s %s %s %s\n", row$Year, cleanCandidate, party, electoralVotes[[1]][j], pop, vp))
    
    # Write to election file
    if (row$Year != "") {
      if (j == 1) {
        # Only write if election year and if first candidate in list
        if (elecNum == 1) {
          write(sprintf("('%s', '%s', '%d')", row$Year, cleanCandidate, elecNum), electionFile, append=TRUE)
        } else {
          write(sprintf("('%s', '%s', '%d'), ", row$Year, cleanCandidate, elecNum), electionFile, append=TRUE)
        }
        elecNum <- elecNum - 1
      }
    }
    
    # Write to participated file
    if (row$Year != "") {
      # Write a participant unless last candidate
      # population and vp are weird in that they might be null so handle that now
      popa <- pop
      if (popa != "NULL") {
        popa <- paste("'",popa, "'", sep="")
      }
      vpa <- vp
      if (vpa != "NULL") {
        vpa <- paste("'",vpa, "'", sep="")
      }
      if (i == nrow(g) &&  j == length(candidates[[1]])) {
        write(sprintf("('%s', '%s', '%s', '%s', %s, %s)", row$Year, cleanCandidate, party, electoralVotes[[1]][j], popa, vpa), participatedFile, append=TRUE)
      } else {
        write(sprintf("('%s', '%s', '%s', '%s', %s, %s), ", row$Year, cleanCandidate, party, electoralVotes[[1]][j], popa, vpa), participatedFile, append=TRUE)
      }
    }
    
  }
}

emptyList<-list()
candList <- union(candList, emptyList) # Set operations to remove duplicates
partyList <- union(partyList, emptyList) 

# Now let's write all parties to a file as a sql command
fileConn <- "party.txt"
write("INSERT INTO `party` (`name`) VALUES ", fileConn)
for (k in 1:length(partyList)){
  if (k == length(partyList)) {
    write(sprintf("('%s')", partyList[[k]]), fileConn, append=TRUE)
  } else {
    write(sprintf("('%s'), ", partyList[[k]]), fileConn, append=TRUE)
  }
}

# Now let's write all candidates to a file as a sql command
fileConn <- "candidate.txt"
write("INSERT INTO `candidate` (`name`) VALUES ", fileConn)
for (k in 1:length(candList)){
  if (k == length(candList)) {
    write(sprintf("('%s')", candList[[k]]), fileConn, append=TRUE)
  } else {
    write(sprintf("('%s'), ", candList[[k]]), fileConn, append=TRUE)
  }
}
