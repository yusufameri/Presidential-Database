library(stringr)
candidates = read.csv("candidate.csv", header=F, sep=",", stringsAsFactors=FALSE)
baseUrl = "http://www.google.com/search?q="
for (i in 1:length(candidates)) {
  webQueryString <- gsub(" ", "+", candidates[[i]])
  url <- sprintf("%s%s%s", baseUrl, webQueryString, "+politician")
  print(candidates[[i]])
  print(url)
  html <- paste(readLines(url), collapse="\n")
  matched <- str_match_all(html, "en.wikipedia.org/wiki/([:alpha:]*\\.?_?)*")
  wikiUrl <- sprintf("%s%s", "http://", matched[[1]][, 1][1]) # Get a url to wikipedia page
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
  print(photo)
}
