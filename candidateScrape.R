library(stringr)
candidates = read.csv("candidate.csv", header=F, sep=",", stringsAsFactors=FALSE, encoding = "UTF-8")
baseUrl = "http://www.google.com/search?q="

candidateFile <- "candidate2.txt" # The candidate file to write
write("DROP TABLE candidate;", candidateFile)
write("CREATE TABLE presidential_elections.candidate ( name VARCHAR(64) NOT NULL , birth_date DATE , death_date DATE , image_url VARCHAR(128) );", candidateFile, append=T)
write("INSERT INTO candidate (name, birth_date, death_date, image_url) VALUES ", candidateFile, append=T)


for (i in 1:length(candidates)) {
  webQueryString <- gsub(" ", "+", candidates[[i]])
  url <- sprintf("%s%s%s", baseUrl, webQueryString, "+politician")
  print(sprintf("%s %d", candidates[[i]], i))
  print(url)
  html <- paste(readLines(url, encoding='UTF-8'), collapse="\n")
  Sys.sleep(1) # Because R is not good for web crawling, need to sleep or it will randomly fail frequently
  matched <- str_match_all(html, "en.wikipedia.org/wiki/([:alpha:]*\\.?_?\\(?\\)?%?[:digit:]?'?)*")
  wikiUrl <- sprintf("%s%s", "http://", matched[[1]][, 1][1]) # Get a url to wikipedia page
  wikiUrl <- gsub("%.*9", "%e9", wikiUrl) # Handle the John Fremont outlier case which gets messed up because of character encoding
  print(wikiUrl)
  wikihtml <- paste(readLines(wikiUrl), collapse="\n")
  bdaymatch <- str_match_all(wikihtml, "span class=\"bday\">(..........)</span") # Get the birthday string if it exists
  deathdaymatch <- str_match_all(wikihtml, "span class=\"dday deathdate\">(..........)</span") # Get the birthday string if it exists
  print(bdaymatch[[1]][, 2][1]) # Great these are already in database format
  print(deathdaymatch[[1]][, 2][1])
  photoUrl <- str_match_all(wikihtml, "src=\"(//upload.*.jpg)\"") #.*src=\"(.*)\"</span") # Get the main photo if it exists
  photo <- photoUrl[[1]][, 2][1]
  photo <- gsub("jpg/.*", "@", photo) # If jpg extension has a slash after replace extension with @ symbol
  photo <- gsub("@", "jpg", photo) # Replace @ symbol back with jpg
  photo <- gsub("/thumb", "", photo) # Remove thumb directory to get actual image link
  print(photo)
  
  # Some Final Sanitizing for putting into SQL format
  cleanCandidate <- gsub("\'", "\\\\\'", candidates[i]) # Escape ' character for sql
  if (!is.na(bdaymatch[[1]][, 2][1])) {
    bday <- paste("'",bdaymatch[[1]][, 2][1], "'", sep="")
  } else {
    bday <- "NULL"
  }
  if (!is.na(deathdaymatch[[1]][, 2][1])) {
    dday <- paste("'",deathdaymatch[[1]][, 2][1], "'", sep="")
  } else {
    dday <- "NULL"
  }
  if (!is.na(photo)) {
    photo <- paste("'",photo, "'", sep="")
  } else {
    photo <- "NULL"
  }
  
  # Now do the file writing
  if (i == length(candidates)) {
    write(sprintf("('%s', %s, %s, %s)", cleanCandidate, bday, dday, photo), candidateFile, append=TRUE)
  } else {
    write(sprintf("('%s', %s, %s, %s),", cleanCandidate, bday, dday, photo), candidateFile, append=TRUE)
  }
  
}
