#!/bin/sh

wget http://labs.zord-git.tk/rosetta/Samples.tar.xz

mkdir Samples
cd Samples
tar xf ../Samples.tar.xz
rm ../Samples.tar.xz
cd ..
