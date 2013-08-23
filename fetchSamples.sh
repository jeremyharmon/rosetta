#!/bin/sh

wget https://www.dropbox.com/s/s9qly6v05j1sktr/Samples.tar.xz

mkdir Samples
cd Samples
tar xf ../Samples.tar.xz
rm ../Samples.tar.xz
cd ..
