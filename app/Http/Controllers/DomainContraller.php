<?php
namespace App\Http\Controllers;

use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Iodev\Whois\Exceptions\ServerMismatchException;
use Iodev\Whois\Factory;

class DomainContraller extends Controller
{

    public function index()
    {
        return view('domain.index');
    }

    public function store(Request $request)
    {
        if (trim($request["domain"]) != "") {
            $domain_arr = explode(" ", $request["domain"]);
            $domain = [];
            for ($i = 0; $i < count($domain_arr); $i++) {
                if (preg_match("#.*|/.* #", $domain_arr[$i])) {
                    $domain[] = $domain_arr[$i];
                }
            }
            $whois = Factory::get()->createWhois();
            $result = [];
            for ($i = 0; $i < count($domain); $i++) {
                try {
                    if ($whois->isDomainAvailable($domain[$i])) {
                        $result[$domain[$i]] = "Этот домен свободен";
                    } else {
                        if(array_key_exists('Registrar Registration Expiration Date',
                            $whois->loadDomainInfo("$domain[$i]")->getExtra()
                        ['groups'][0])){
                            $result[$domain[$i]] = $whois->loadDomainInfo("$domain[$i]")->getExtra()
                            ['groups'][0]['Registrar Registration Expiration Date'];
                        }
                        else{
                            $result[$domain[$i]] = $whois->loadDomainInfo("$domain[$i]")->getExtra()
                            ['groups'][0]['Registry Expiry Date'];
                        }
                    }
                } catch (ServerMismatchException $e) {
                    $result[$domain[$i]] = "Неверный формат доменного имени";
                }

            }

        }
        dd($result);
        return redirect('domain.index');
    }
}
