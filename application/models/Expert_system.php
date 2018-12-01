<?php

use Symfony\Component\Serializer\Encoder\JsonEncoder;

class Expert_system extends CI_Model
{
    protected $api_url;
    protected $encoder;

    public function __construct()
    {
        parent::__construct();

        $this->api_url = getenv('API_URL');
        $this->encoder = new JsonEncoder();
    }

    public function decode_response($response)
    {
        return $this->encoder->decode($response, JsonEncoder::FORMAT, [
            'json_decode_associative' => true
        ]);
    }

    public function prepare_request($ch, $value = null)
    {
        $url = $this->api_url . urlencode($value) ?: '';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    }

    public function get_response($ch)
    {
        $response = curl_exec($ch);

        curl_close($ch);

        $decoded_response = $this->decode_response($response);

        $this->validate_response($decoded_response);

        return $decoded_response;
    }

    public function get_statements()
    {
        $ch = curl_init();

        $this->prepare_request($ch);

        return $this->get_response($ch);
    }

    public function get_statement($value = 'root')
    {
        $ch = curl_init();

        $this->prepare_request($ch, $value);

        return $this->get_response($ch);
    }

    public function create_statement(
        $statement_value,
        $parent_statement_value,
        $parent_relationship_value,
        $parent_relationship_support_level_value
    ) {
        $ch = curl_init();
        $data_string = $this->encoder->encode([
            'statement_value' => $statement_value,
            'parent_statement_value' => $parent_statement_value,
            'parent_relationship_value' => $parent_relationship_value,
            'parent_relationship_support_level_value' => (int)$parent_relationship_support_level_value
        ], JsonEncoder::FORMAT);

        $this->prepare_request($ch);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);

        $response = $this->get_response($ch);

        return $response;
    }

    public function update_statement(
        $statement_value,
        $new_statement_value,
        $new_parent_statement_value,
        $new_parent_relationship_value,
        $new_parent_relationship_support_level_value
    ) {
        $ch = curl_init();
        $data_string = $this->encoder->encode([
            'statement_value' => $statement_value,
            'new_statement_value' => $new_statement_value,
            'new_parent_statement_value' => $new_parent_statement_value,
            'new_parent_relationship_value' => $new_parent_relationship_value,
            'new_parent_relationship_support_level_value' => (int)$new_parent_relationship_support_level_value
        ], JsonEncoder::FORMAT);

        $this->prepare_request($ch);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);

        $response = $this->get_response($ch);

        return $response;
    }

    public function remove_statement($statement_value, $with_children = false)
    {
        $ch = curl_init();
        $data_string = $this->encoder->encode([
            'statement_value' => $statement_value,
            'with_children' => $with_children
        ], JsonEncoder::FORMAT);

        $this->prepare_request($ch);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);

        $response = $this->get_response($ch);

        return $response;
    }

    public function import($url, $appendMode = false, $is_external_url = false)
    {
        $ch = curl_init();
        $data_string = $this->encoder->encode([
            'url' => $is_external_url ? $url : 'file:///' . urlencode($url),
            'options' => [
                'append_mode' => $appendMode
            ],
        ], JsonEncoder::FORMAT);

        $this->prepare_request($ch, 'import');

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);

        $response = $this->get_response($ch);

        return $response;
    }

    /**
     * @param $response
     * @throws Exception
     */
    public function validate_response($response)
    {
        if (array_key_exists('error', $response)) {
            throw new Exception($response['error']);
        }
    }

    /**
     * @return array
     */
    public function get_statement_tree_array()
    {
        $ch = curl_init();

        $this->prepare_request($ch);

        curl_setopt($ch, CURLOPT_URL, $this->api_url . '?withParents');

        $statements = $this->get_response($ch);

        return $this->buildTree($statements);
    }

    /**
     * @param array $statements
     * @param null $parent_statement_value
     * @return array
     */
    public function buildTree(array &$statements, $parent_statement_value = null)
    {
        $branch = [];

        foreach ($statements as $statement) {
            if ($statement['parent_statement_value'] == $parent_statement_value) {
                $child_statements = $this->buildTree($statements, $statement['statement_value']);

                $statement['child_statements'] = $child_statements ?: [];

                $branch[$statement['statement_value']] = $statement['child_statements'];

                unset($branch[$statement['statement_value']]['statement_value']);
                unset($branch[$statement['statement_value']]['parent_statement_value']);
            }

        }

        return $branch;
    }
}
