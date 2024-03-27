#!/bin/sh
cd $PROJECT_DIR
make

(while true
do
 php console.php queue:consume package-queue --item-limit 10
done) &

