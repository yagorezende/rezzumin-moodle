#!/bin/bash
# shellcheck disable=SC2164
cd rezzumin_nlp
COUNT=`ll | grep -c venv`
if [ $COUNT = 0 ]; then
    sudo apt install -y gcc python3-venv python3-dev
    python3 -m venv venv
    source venv/bin/activate
    pip install --upgrade pip
    pip install -r requirements.txt
    python3 -c "import nltk;nltk.download('rslp');nltk.download('stopwords');nltk.download('punkt')"
    echo 'Done.'
else
    echo "Nothing to do."
fi
