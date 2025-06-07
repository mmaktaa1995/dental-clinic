#!/bin/bash

# Function to recursively process all test files
process_test_files() {
    # Convert all method names to camelCase
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function (test_|it_|)([a-zA-Z0-9_]+)\(/function \u$2\(/g' {} \;
    
    # Convert first character to lowercase (except for setUp)
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function (?!setUp)([A-Z])/function \l$1/g' {} \;
    
    # Fix the setUp method
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function (s|S)etup\(/function setUp\(/g' {} \;
    
    # Handle numbers in method names
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-z]+)([0-9]+)([a-zA-Z])/function $1$2\u$3/g' {} \;
    
    # Handle special cases like "404" in method names
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function (returns404)_/function $1/g' {} \;
    
    # Convert remaining underscores to camelCase (handle multiple underscores)
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-zA-Z0-9]+)_([a-zA-Z0-9])/function $1\u$2/g' {} \;
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-zA-Z0-9]+)_([a-zA-Z0-9])/function $1\u$2/g' {} \;
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-zA-Z0-9]+)_([a-zA-Z0-9])/function $1\u$2/g' {} \;
    
    # Fix any double uppercase letters
    find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-z])([A-Z])([A-Z])/function $1$2\l$3/g' {} \;
}

# Run the processing
process_test_files

# Fix any remaining specific patterns
find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-z]+)In([A-Z])/function $1In\l$2/g' {} \;
find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-z]+)On([A-Z])/function $1On\l$2/g' {} \;
find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-z]+)With([A-Z])/function $1With\l$2/g' {} \;
find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-z]+)For([A-Z])/function $1For\l$2/g' {} \;
find tests/ -type f -name "*Test.php" -exec perl -i -pe 's/function ([a-z]+)To([A-Z])/function $1To\l$2/g' {} \;
