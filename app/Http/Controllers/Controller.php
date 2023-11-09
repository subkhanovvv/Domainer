<?php

namespace App\Http\Controllers;

use Iodev\Whois\Factory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Iodev\Whois\Exceptions\ConnectionException;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Exceptions\WhoisException;
use Iodev\Whois\Loaders\CurlLoader;
use Iodev\Whois\Modules\Tld\TldServer;
use Iodev\Whois\Loaders\SocketLoader;
use Iodev\Whois\Loaders\MemcachedLoader;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function whois()
    {
        // Creating default configured client
        $whois = Factory::get()->createWhois();

        // Checking availability
        if ($whois->isDomainAvailable("google.com")) {
            print "Bingo! Domain is available! :)";
        }

        // Supports Unicode (converts to punycode)
        if ($whois->isDomainAvailable("почта.рф")) {
            print "Bingo! Domain is available! :)";
        }

        // Getting raw-text lookup
        $response = $whois->lookupDomain("google.com");
        print $response->text;

        // Getting parsed domain info
        $info = $whois->loadDomainInfo("google.com");
        print_r([
            'Domain created' => date("Y-m-d", $info->creationDate),
            'Domain expires' => date("Y-m-d", $info->expirationDate),
            'Domain owner' => $info->owner,
        ]);
    }
    public function whoIs1()
    {
        try {
            $whois = Factory::get()->createWhois();
            $info = $whois->loadDomainInfo("google.com");
            if (!$info) {
                print "Null if domain available";
                exit;
            }
            print $info->domainName . " expires at: " . date("d.m.Y H:i:s", $info->expirationDate);
        } catch (ConnectionException $e) {
            print "Disconnect or connection timeout";
        } catch (ServerMismatchException $e) {
            print "TLD server (.com for google.com) not found in current server hosts";
        } catch (WhoisException $e) {
            print "Whois server responded with error '{$e->getMessage()}'";
        }
    }

    public function FunctionName()
    {
        $loader = new CurlLoader();
        $loader->replaceOptions([
            CURLOPT_PROXYTYPE => CURLPROXY_SOCKS5,
            CURLOPT_PROXY => "127.0.0.1:1080",
            //CURLOPT_PROXYUSERPWD => "user:pass",
        ]);
        $whois = Factory::get()->createWhois($loader);

        var_dump([
            'ya.ru' => $whois->loadDomainInfo('ya.ru'),
            'google.de' => $whois->loadDomainInfo('google.de'),
        ]);
    }

    public function whoIs2()
    {




        $whois = Factory::get()->createWhois();

        // Define custom whois host
        $customServer = new TldServer(".custom", "whois.nic.custom", false, Factory::get()->createTldParser());

        // Or define the same via assoc way
        $customServer = TldServer::fromData([
            "zone" => ".custom",
            "host" => "whois.nic.custom",
        ]);

        // Add custom server to existing whois instance
        $whois->getTldModule()->addServers([$customServer]);

        // Now it can be utilized
        $info = $whois->loadDomainInfo("google.custom");

        var_dump($info);
    }
    public function whoIs3()
    {
        $whois = Factory::get()->createWhois();

        // Add default servers
        $matchedServers = $whois->getTldModule()
            ->addServers(TldServer::fromDataList([
                ['zone' => '.*.net', 'host' => 'localhost'],
                ['zone' => '.uk.*', 'host' => 'localhost'],
                ['zone' => '.*', 'host' => 'localhost'],
            ]))
            ->matchServers('some.uk.net');

        foreach ($matchedServers as $s) {
            echo "{$s->getZone()}  {$s->getHost()}\n";
        }

// Matched servers + custom defaults:
//
// .uk.net  whois.centralnic.com
// .uk.net  whois.centralnic.net
// .uk.*  localhost
// .*.net  localhost
// .net  whois.crsnic.net
// .net  whois.verisign-grs.com
// .*  localhost
    }
    public function whoIs4(){
        $whois = Factory::get()->createWhois();

// Getting raw-text lookup
$response = $whois->lookupAsn("AS32934");
print $response->text;

// Getting parsed ASN info
$info = $whois->loadAsnInfo("AS32934");
foreach ($info->routes as $route) {
    print_r([
        'route IPv4' => $route->route,
        'route IPv6' => $route->route6,
        'description' => $route->descr,
    ]);   
}
    }
    public function whoIs5(){
        $m = new Memcached();
$m->addServer('127.0.0.1', 11211);
$loader = new MemcachedLoader(new SocketLoader(), $m);

$whois = Factory::get()->createWhois($loader);
// do something...
    }
}
