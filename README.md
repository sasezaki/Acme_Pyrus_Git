WARNING : This is a protoype pyrus plugin-command for git.

SETUP
=====
<pre>
$ pyrus.phar install -p pear/VersionControl_Git-alpha
$ pyrus.phar package 
$ pyrus.phar install -p Acme_Pyrus_Git-0.1.0.tgz
</pre>

SAMPLE
==================

install from remote (require package.xml)
<pre>
$ pyrus.phar install-git -g git://github.com/diggin/Diggin_Http_Charset.git
</pre>


local packaging from remote
<pre>
$ pyrus.phar package-git -g git://github.com/diggin/Diggin_Http_Charset.git
</pre>

local copy and paste to php_dir (no-require package.xml)
<pre>
$ pyrus.phar copy-git -g git://github.com/diggin/Diggin_Http_Charset.git
</pre>
