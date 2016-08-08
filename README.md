BubnovTensorFlowBundle
=================

This bundle provides service and command to recognize image.
Both will return RecognizeResult object.

### Requirements

- PHP 5.4 or higher
- symfony 2.3


### Installation

To install BubnovTensorFlowBundle with Composer just add the following to your 'composer.json' file:

    {
        require: {
            "bubnovKelnik/tensorflow-bundle": "*",
            ...
        },
        ...
        "scripts": {
            "post-install-cmd": [ "TensorFlowBinaryInstaller::install" ],
            "post-update-cmd":  [ "TensorFlowBinaryInstaller::update" ]
        },
        ...
        repositories: [
            {
                "type": "vcs",
                "url":  "git@github.com:bubnovKelnik/tenserflow-bundle.git"
            },
            ...
        ]
    }

The next thing you should do is install the bundle by executing the following command:

    php composer.phar update bubnovKelnik/tensorflow-bundle

Finally, add the bundle to the registerBundles function of the AppKernel class in the 'app/AppKernel.php' file:

    public function registerBundles()
    {
        $bundles = array(
            ...
            new Bubnov\TensorFlowBundle\BubnovTensorFlowBundle(),
            ...
        );

### Usage

When you want to use service from the controller you can simply call:

    /* @var $result \Bubnov\TensorFlowBundle\Util\RecognizerResult */
    $result = $this->get('tenserflow.recognizer')->recognize('some/path/to/image'); //Returns RecognizerResult object

    /* @var $label \Bubnov\TensorFlowBundle\Util\Label */
    $label = $result->getTopLabel();           // Returns Label with highest score
    $label->getName();                         // Returns label`s name
    $label->getScore();                        // Returns label`s score

    $labels = $result->getLabels();            // Returns array of Label in order of score.
    $labels = $result->getLabelsScored(0.8);   // Returns array of Label in order of score with score more or equal 0.8

    /**
     * Find if there are any labels in the Dictionary
     */
    $dict = new \Bubnov\TensorFlowBundle\Util\Dictionary(['apple', 'fruit']);
    $dict->add('blueberry');                         // Add string to the dictionary
    
    $scoreThreshold = 0.7;                           // Optional threshold
    $dict->match($result, $scoreThreshold);          // Return true or false

Where is also the command for testing images "bubnov_tensorflow:recognize". Usage:

    bubnov_tensorflow:recognize /absolute/path/to/image.ext

Command will return multiline string with labels and scores

To create dictionary with common labels to the same images in some directory, you can call:

    find /path/to/dir/with/images -type f | parallel ./app/console bubnov_tensorflow:fill_dict {} --tmpdict /path/to/dict.tmp

This command using GNU parallel. Optional --tmpdict may be omitted - temporary dict file will be saved to /tmp/tensorFlowBundle.dict.tmp
After complete, call:

    app/console bubnov_tensorflow:combine_dict --tmpdict /path/to/dict.tmp --dict /path/to/complete.dict

This command will create dictionary with labels and the number of their repetitions in the dict.tmp file.
Now you may choose, with labels to include in Dictionary.
Optional --dict may be omitted - dict file will be saved to /tmp/tensorFlowBundle.dict
Optional --tmpdict may be omitted - temporary dict file will be read from /tmp/tensorFlowBundle.dict.tmp


### Configuration

This bundle will work with standalone configuration, but you may redefine some paths. It is optional and not necessary

```yml
bubnov_tensor_flow:
    recognizer:
        binary: 'some/path/to/label_image binary'
        graph: 'some/path/to/graph.pb'
        labels: 'some/path/to/labels.txt'

```


### Credits

This bundle is a wrapper around tenderflow`s label_image binary with tiny updates:
https://github.com/tensorflow/tensorflow by The TensorFlow Authors

Bundle code is written by Mikhail Bubnov
bubnov.mihail@gmail.com
https://github.com/bubnovKelnik


### License

This bundle is under the MIT license.
