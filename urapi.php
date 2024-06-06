<?php
require_once 'vendor/autoload.php';

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

while (true)
{
    $searchTerm = trim(readline("Enter company name to search: "));


    $url = 'https://data.gov.lv/dati/lv/api/3/action/datastore_search?q=' . $searchTerm .
        '&resource_id=25e80bf3-f107-4ab4-89ef-251b5b9374e9';

    $response = file_get_contents($url);

    if ($response !== false) {

        $data = json_decode($response);

        if ($data && isset($data->result->records)) {
            echo "Search results: " . count($data->result->records) . "\n";

            $output = new ConsoleOutput();
            $table = new Table($output);

            $table->setHeaders(['Name', 'Registration number', 'Registration form', 'Registration date']);

            foreach ($data->result->records as $company) {
                $table->addRow([$company->name, $company->regcode, $company->type,
                    $company ->registered . "\n"]);
            }
            $table->render();

            $again = readline("Do you want to continue searching? (y/n): ");
            if ($again == 'n') {
                break;
            }

        } else {
            echo "No results found for \"$searchTerm\"" . "\n";
        }
    } else {
        echo "Failed to connect to data.gov.lv.";
    }
}