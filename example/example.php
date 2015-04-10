<?hh

require_once(__DIR__.'/../vendor/autoload.php');

const USE_BROWSERIFY = true;

class :test extends :x:element {
  use XHPHelpers;
  use XHPJSCall;
  use XHPJSInstance;

  attribute :xhp:html-element;

  protected function render(): XHPRoot {
    /* Roughly equivalent to:
     *
     * var MyJSModule = require('MyJSModule');
     * MyJSModule.myJSFunction(
     *  'hello, world',
     *  <result of constructJSInstance() call below>
     * );
     *
     * The JS code realizes it needs to construct the JS class first, despite
     * the call below.
     */
    $this->jsCall(
      'MyJSModule',
      'myJSFunction',
      'hello, world.',
      XHPJS::Instance($this)
    );

    /*
     * var MyJSController = require('MyJSController');
     * new MyJSController(
     *   document.getElementById(< $this->getID() >);
     *   'herp derp'
     * );
     */
    $this->constructJSInstance(
      'MyJSController',
      XHPJS::Element($this),
      'herp derp',
    );

    return <div id={$this->getID()}>In :test::render()</div>;
  }
}

class :react-test extends :x:element {
  use XHPHelpers;
  use XHPReact;

  attribute
    :xhp:html-element,
    string some-attribute @required;

  protected function render(): XHPRoot {
    // Self-explanatory :)
    $this->constructReactInstance(
      'MyReactClass',
      Map {'someAttribute' => $this->:some-attribute }
    );
    return <div id={$this->getID()} />;
  }
}

$script = null;
if (USE_BROWSERIFY) {
  // We recommend using npm and browserify...
  $script = <script src="build/bundle.js" />;
} else {
  // ... but you don't have to :)
  $script =
    <x:frag>
      <script src="http://fb.me/react-0.13.1.js" />

      <script src="js/XHPJS.js" />

      <script src="js/MyJSModule.js" />
      <script src="js/MyJSController.js" />

      <!-- src/MyReactClass.js contains JSX -->
      <script src="build/MyReactClass.js" />
    </x:frag>;
}

$xhp = 
  <html>
    <head>
      {$script}
    </head>
    <body>
      <x:js-scope>
        <test />
        <react-test some-attribute="some value" />
      </x:js-scope>
    </body>
  </html>;
print $xhp;
