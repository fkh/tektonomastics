#!/usr/bin/env python2.5

print "Content-type: text/html\n\n"

#import simplegeo

import oauth2 as oauth

import simplejson as json

import geojson

#try:

# Create your consumer with the proper key/secret.
consumer = oauth.Consumer(key="Geds2UZQpuN5Fs5TmfrEngpd8YUMKrdL", secret="Cv35xjrXeFMSRbuYg7sp9SckGXghyatT")

request_token_url = "http://api.simplegeo.com/0.1/records/fkhtest/1.json"

# Create our client.
client = oauth.Client(consumer)

# The OAuth Client request works just like httplib2 for the most part.
resp, content = client.request(request_token_url, "GET")

# print content

a = json.loads(content)

print a['created']

print "EOF"

#make a new record

json.dumps(['foo', {'bar': ('baz', None, 1.0, 2)}])


{ "type": "Point", "properties": { "id": "4181671071" }, "coordinates": [ 37.706350999999998, -122.417371 ] }




# authenticate
#client = simplegeo.Client('Geds2UZQpuN5Fs5TmfrEngpd8YUMKrdL', 'Cv35xjrXeFMSRbuYg7sp9SckGXghyatT')

# add a new record
#rec = simplegeo.Record('fkhtest','2','41.6','-72.9','place','bname=TheDearborn')

#client.add_record(rec)

#print client.get_nearby('fkhtest', '40.6,-73.9')
