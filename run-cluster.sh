#!/bin/bash


# Start the first container/node:
echo "Running Node 1... "
docker run -d \
--name=roach1 \
--hostname=roach1 \
--net=roachnet \
-p 26257:26257 -p 8080:8080  \
-v "${PWD}/cockroach-data/roach1:/cockroach/cockroach-data"  \
cockroachdb/cockroach:v1.0.1 start --insecure

# Start the second container/node:
echo "Running Node 2... "
docker run -d \
--name=roach2 \
--hostname=roach2 \
--net=roachnet \
-v "${PWD}/cockroach-data/roach2:/cockroach/cockroach-data" \
cockroachdb/cockroach:v1.0.1 start --insecure --join=roach1

# Start the third container/node:
echo "Running Node 3... "
docker run -d \
--name=roach3 \
--hostname=roach3 \
--net=roachnet \
-v "${PWD}/cockroach-data/roach3:/cockroach/cockroach-data" \
cockroachdb/cockroach:v1.0.1 start --insecure --join=roach1



