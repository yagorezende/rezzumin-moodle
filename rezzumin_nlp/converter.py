#!/usr/bin/python3
# -*- coding: UTF-8 -*-
import sys
import os
import re
import nltk
import string
import operator
import PyPDF2
import textract
import random
import rouge
import pandas as pd
import numpy as np
import seaborn as sns
import preprocessor as p
import networkx as nx
import matplotlib.pyplot as plt
from math import sqrt
from rouge import Rouge
from fuzzywuzzy import fuzz
from time import time, sleep
from textblob import TextBlob
from nltk.corpus import stopwords
from sklearn.svm import OneClassSVM
from nltk.tokenize import word_tokenize
from sklearn.metrics import accuracy_score, confusion_matrix, f1_score, precision_score,recall_score
from sklearn.model_selection import train_test_split, GridSearchCV
from sklearn.metrics.pairwise import cosine_similarity, euclidean_distances
from sklearn.feature_extraction.text import TfidfVectorizer, CountVectorizer
from sklearn.decomposition import PCA, TruncatedSVD
from sklearn.cluster import KMeans, DBSCAN
from sklearn_extra.cluster import KMedoids
from sklearn.metrics import silhouette_score
from sklearn.preprocessing import normalize
from scipy.stats import ttest_1samp, ttest_ind, ttest_rel, norm, pearsonr

class Converter:
    def __init__(self, text: str, _id: int, percent: int):
        self.text = text
        self.filename = self.text.__hash__()
        self.id = _id
        self.percent = percent
        self.done = False
        self.fail = False
        self.result = None

    def process(self):
        sentences = self.text.split('.')
        COL = ['Sentences']
        database = pd.DataFrame(sentences, columns=COL)
        csvFile = open(f"/tmp/{self.filename}.csv", 'w' , encoding='utf-8')
        database.to_csv(csvFile, mode="w", columns=COL, index=False, encoding='utf-8')
        csvFile.close()
        self.result = self.__digest("/tmp")
        return self

    def __digest(self, root_path):
        print("Digesting....")
#         if file_request.isPdf:
#             file_request.fullText = pdf_txt_converter(file_request)  #+10%
#             file_request.status = 30
#
#             if file_request.isSBC:
#                 file_request.cleanupText()  #+10%
#             else:
#                 file_request.body = file_request.fullText
#             file_request.incrementStatus(10)
#         else:
#             file_request.status = 30

#         text_reading(file_request)  #+10%
#         file_request.incrementStatus(10)
        try:
            names_dict = self.get_name_dict("./data")  #+10%
            print("get_name_dict Done!")
    #         file_request.incrementStatus(10)

            self.preprocessing(names_dict, root_path)  #+10%
            print("preprocessing Done!")
    #         file_request.incrementStatus(10)

            self.position_weighted_metric(root_path)  #+10%
            print("preprocessing Done!")
    #         file_request.incrementStatus(10)

            self.graph_methodology(root_path)  #+10%
            print("preprocessing Done!")
    #         file_request.incrementStatus(10)

            # tfidf_cossine_euclidean(file_request.path)  #+10%
            # file_request.incrementStatus(10)
            #
            # brush_path(file_request.path)  #+10%
            # file_request.incrementStatus(10)

            output = self.k_medoids_method(root_path)  #+10%
            print("preprocessing Done!")
    #         file_request.incrementStatus(10)

            print(f"########## TEXT OUTPUT ########## \n \
            {output} \n\
            ########## TEXT OUTPUT ########## \n")
            self.done = True
            return output
        except Exception as e:
            self.fail = True
            self.done = True
            raise e
            return None

    def similarity(self, word, names):
    	try:
    		for w in names[word[0]]:
    			if(fuzz.ratio(w, word) > 80):
    				return True
    	except KeyError:
    		pass
    	return False

    def get_name_dict(self, root_path):
    	print("get_name_dict...")
    	names = open("{}/names.txt".format(root_path), encoding="utf-8")
    	names_lines = names.readlines()
    	names_dict = {}
    	for lines in names_lines:
    		line = lines.replace('\n','').lower()
    		if line[0] not in names_dict: names_dict[line[0]] = []
    		names_dict[line[0]].append(line)
    	names.close()
    	return names_dict

    def position_weighted_metric(self, root_path):
    	print("position_weighted_metric...")
    	df = pd.read_csv(f"{root_path}/cleaned_{self.filename}.csv", header=0, encoding="utf-8")
    	tfidf_vectorizer = TfidfVectorizer(ngram_range=(1, 1))
    	count_vectorizer = CountVectorizer(ngram_range=(1, 1))
    	tfidf_matrix = tfidf_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()
    	count_matrix = count_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()
    	'''Assume general form: (A,B) C
    	A: Document index
    	B: Specific word-vector index
    	C: TFIDF score for word B in document A'''
    	position_measures = []
    	for row in range(len(tfidf_matrix)):
    		position_measures.append(1 - ((row) / len(tfidf_matrix)))
    	df['Position'] = position_measures

    	COLS = ['Sentences', 'Cleaned_Sentences', 'Position']
    	csvFile = open(f"{root_path}/cleaned_{self.filename}.csv", 'w' ,encoding='utf-8')
    	df.to_csv(csvFile, mode='w', columns=COLS, index=False, encoding="utf-8")
    	csvFile.close()

    def tfidf_metric(self, root_path):
    	print("tfidf_metric...")
    	df = pd.read_csv(f"{root_path}/cleaned_{self.filename}.csv", header=0, encoding="utf-8")
    	#print(df.to_string())
    	tfidf_vectorizer = TfidfVectorizer(ngram_range=(1,1))
    	count_vectorizer = CountVectorizer(ngram_range=(1,1))
    	tfidf_matrix=tfidf_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()
    	count_matrix=count_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()

    	tfidf_measures=[]
    	for row in range(len(tfidf_matrix)):
    		tfidf_measures.append(sum(np.asarray(tfidf_matrix)[row])/len(np.asarray(tfidf_matrix)[row]))
    	df['TDIDF_Average'] = tfidf_measures

    	COLS = ['Sentences', 'Cleaned_Sentences','Position','TDIDF_Average']
    	csvFile = open(f"{root_path}/cleaned_{self.filename}.csv", 'w' ,encoding='utf-8')
    	df.to_csv(csvFile, mode='w', columns=COLS, index=False, encoding="utf-8")
    	csvFile.close()

    def tfidf_cossine_euclidean(self, root_path):
    	print("tfidf_cossine_euclidean...")
    	df = pd.read_csv(f"{root_path}/cleaned_{self.filename}.csv",header=0)
    	tfidf_vectorizer = TfidfVectorizer(ngram_range=(1,1))
    	count_vectorizer = CountVectorizer(ngram_range=(1,1))
    	tfidf_matrix=tfidf_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()
    	count_matrix=count_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()

    	euclidean_measures=[]
    	cosine_measures=[]
    	for index in range(len(tfidf_matrix)):
    		euclidean=0
    		cosine=0
    		for row in tfidf_matrix:
    			euclidean+=euclidean_distances(tfidf_matrix[index],row)
    			cosine+=cosine_similarity(tfidf_matrix[index],row)

    		euclidean=float(str(euclidean).replace(']]', '').replace('[[', '')) #Convert a single value matrix into a float
    		cosine=float(str(cosine).replace(']]', '').replace('[[', '')) #Convert a single value matrix into a float
    		euclidean_measures.append(euclidean/len(tfidf_matrix))
    		cosine_measures.append(cosine/len(tfidf_matrix))

    	df['Euclidean Average'] = euclidean_measures
    	df['Cosine Average'] = cosine_measures
    	#print (df['Cosine Average'])
    	#print (df['Euclidean Average'])

    	COLS = ['Sentences', 'Cleaned_Sentences','Position','TDIDF_Average','Euclidean Average','Cosine Average']
    	csvFile = open(f"{root_path}/cleaned_{self.filename}.csv", 'w' ,encoding='utf-8')
    	df.to_csv(csvFile, mode='w', columns=COLS, index=False, encoding="utf-8")
    	csvFile.close()

    def brush_path(self, root_path):
    	print("brush_path...")
    	df = pd.read_csv(f"{root_path}/cleaned_{self.filename}.csv",header=0)
    	tfidf_vectorizer = TfidfVectorizer(ngram_range=(1,1))
    	count_vectorizer = CountVectorizer(ngram_range=(1,1))
    	tfidf_matrix=tfidf_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()
    	count_matrix=count_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()

    	G = nx.Graph()
    	G.add_nodes_from(df.index)
    	print
    	pass
    	for index in range(len(tfidf_matrix)):
    		cosine=0
    		for row in range(len(tfidf_matrix)):
    			cosine=cosine_similarity(tfidf_matrix[index],tfidf_matrix[row])
    			cosine=float(str(cosine).replace(']]', '').replace('[[', ''))
    			#if index == 30:
    				#print(cosine)

    			if (cosine>=0.1) and (cosine!=1.0) :
    				G.add_weighted_edges_from([(index,row,cosine)])

    	#pos = nx.spring_layout(G)
    	pos = nx.fruchterman_reingold_layout(G)
    	labels = nx.get_edge_attributes(G,'weight')
    	#pos = nx.circular_layout(G)
    	#pos = nx.shell_layout(G)

    	nx.draw(G,pos, with_labels=True, font_weight='bold')
    	#plt.show()

    	degree=[]
    	betweenness=[]
    	for value in nx.betweenness_centrality(G).values():
    		betweenness.append(value)
    	for value in nx.degree_centrality(G).values():
    		degree.append(value)

    	df['Betweenness'] = betweenness
    	df['Degree'] = degree

    	#print(nx.number_connected_components(G)) #3
    	components=[]
    	for component in nx.connected_components(G):
    		components.append(component)

    	names=[]
    	for c in range(len(components)):
    		values=[]
    		for index in range(len(betweenness)):
    			if index in components[c]:
    				values.append(betweenness[index])
    			else:
    				values.append(0)
    		names.append('Feature '+str(c))
    		df[names[c]] = values
    		#print(df[names[c]])

    	COLS = ['Sentences', 'Cleaned_Sentences','Position','TDIDF_Average','Euclidean Average','Cosine Average', 'Betweenness','Degree']
    	for i in range(len(names)):
    		COLS.append(names[i])

    	csvFile = open(f"{root_path}/cleaned_{self.filename}.csv", 'w' ,encoding='utf-8')
    	df.to_csv(csvFile, mode='w', columns=COLS, index=False, encoding="utf-8")
    	csvFile.close()

    def graph_methodology(self, root_path):
    	df = pd.read_csv(f"{root_path}/cleaned_{self.filename}.csv", header=0)
    	tfidf_vectorizer = TfidfVectorizer(ngram_range=(1, 1))
    	COLS = list(df.columns)
    	tfidf_matrix = tfidf_vectorizer.fit_transform(df["Cleaned_Sentences"].values.astype('U')).todense()

    	G = nx.Graph()
    	G.add_nodes_from(df.index)

    	for index in range(len(tfidf_matrix)):
    		cosine = 0
    		for row in range(len(tfidf_matrix)):
    			cosine = cosine_similarity(tfidf_matrix[index], tfidf_matrix[row])
    			cosine = float(str(cosine).replace(']]', '').replace('[[', ''))
    			# if index == 4:
    			# print(cosine)

    			if (cosine >= 0.1) and (cosine != 1.0):
    				G.add_weighted_edges_from([(index, row, cosine)])

    	# pos = nx.spring_layout(G)
    	pos = nx.fruchterman_reingold_layout(G)
    	labels = nx.get_edge_attributes(G, 'weight')
    	# pos = nx.circular_layout(G)
    	# pos = nx.shell_layout(G)

    	nx.draw(G, pos, with_labels=True, font_weight='bold')
    	# plt.show()

    	betweenness = []
    	maximmum = []
    	edges = G.number_of_edges()
    	while edges > 0:
    		current_max = max(nx.betweenness_centrality(G).items(), key=operator.itemgetter(1))[0]
    		maximmum.append(current_max)
    		G.remove_node(current_max)
    		edges = G.number_of_edges()

    	rows, colums = df.shape
    	feature = np.zeros(rows)
    	names = []
    	for value in range(len(maximmum)):
    		feature[maximmum[value]] = 1
    		names.append('Feature List' + str(value))
    		df[names[value]] = feature
    		feature = np.zeros(rows)

    	for i in range(len(names)):
    		COLS.append(names[i])

    	csvFile = open(f"{root_path}/cleaned_{self.filename}.csv", 'w', encoding='utf-8')
    	df.to_csv(csvFile, mode='w', columns=COLS, index=False, encoding="utf-8")
    	csvFile.close()


    def k_medoids_method(self, root_path):
    	database = pd.read_csv(f"{root_path}/cleaned_{self.filename}.csv", header=0)
    	database = database[database['Sentences'].notna()]
    	df = database.drop(['Sentences', 'Cleaned_Sentences'], axis=1)
    	row, col = df.shape
    	clusters = int(row * int(self.percent) / 100)
    	print(clusters)
    	if not clusters : clusters+= 1
    	kmedoids = KMedoids(n_clusters=clusters, random_state=0).fit(df)
    	centers = pd.DataFrame(kmedoids.cluster_centers_, columns=df.columns)

    	output = database.loc[df['Position'].isin(centers['Position'])]
    	output_abstract = '.'.join(output['Sentences'].to_list())
    	return output_abstract


    # print (output_abstract)

    def preprocessing(self, names_dict, root_path):
    	print("preprocessing...")
    	stemmer = nltk.stem.RSLPStemmer()
    	stop_words = set(stopwords.words('portuguese'))

    	df = pd.read_csv(f"{root_path}/{self.filename}.csv", header=0, encoding="utf-8")

    	print(df.to_string())
    	df["Cleaned_Sentences"] = df["Sentences"].str.lower()
    	df["Cleaned_Sentences"] = df["Cleaned_Sentences"].apply(
    		lambda x: ' '.join(self.remove_punctuation(word) for word in word_tokenize(str(x))))
    	df["Cleaned_Sentences"] = df["Cleaned_Sentences"].apply(
    		lambda x: ' '.join(word for word in word_tokenize(x) if word not in stop_words))
    	df["Cleaned_Sentences"] = df["Cleaned_Sentences"].apply(
    		lambda x: ' '.join(stemmer.stem(word) for word in word_tokenize(x) if not self.similarity(word, names_dict)))

    	COLS = ['Sentences', 'Cleaned_Sentences']
    	csvFile = open(f"{root_path}/cleaned_{self.filename}.csv", 'w', encoding='utf-8')
    	df.to_csv(csvFile, mode='w', columns=COLS, index=False, encoding="utf-8")
    	csvFile.close()

    def remove_punctuation(self, word):
    	for ch in string.punctuation:
    		word = word.replace(ch, "")
    	if(len(word) < 2 or word.isdigit()):
    		return ""
    	return word