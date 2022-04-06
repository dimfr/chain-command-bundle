#ChainCommandBundle

Symfony bundle that implements command chaining functionality.

##Installation

1. Clone repository https://github.com/dimfr/chain-command-bundle.git
```
git clone https://github.com/dimfr/chain-command-bundle.git
```
3. Run composer installation within project directory
```
composer install
```
4. Add tag 'chain.command' to initialize chain
```
  Dimfr\Bundle\BarBundle\Command\HiCommand:
    tags:
      - { name: 'console.command' }
      - { name: 'chain.command', master: 'foo:hello', priority: 1 }
```
5. Check result
```
php bin/console foo:hello
Hello from Foo!
Hi from Bar!
```