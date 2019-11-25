#  add in composer.json file

"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/Parthmy007/ttibi_arch.git"
    }
]

# install package "ttibi/architecture" 

composer require ttibi/architecture

#  once package install publish the package files

 php artisan vendor:publish

# display list of thinks to publish 
# we have to select  "Provider: TTIBI\Architecture\ArchServiceProvider"

 [7 ] Provider: TTIBI\Architecture\ArchServiceProvider

# enter "7" and press enter key
