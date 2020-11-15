<?hh
/**
 * Copyright (c) 2015-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the BSD-style license found in the
 * LICENSE file in the root directory of this source tree. An additional grant
 * of patent rights can be found in the PATENTS file in the same directory.
 */

use type Facebook\XHP\HTML\HasXHPHTMLHelpers;

trait XHPReact {
  use XHPJSCall;
  require implements HasXHPHTMLHelpers;

  protected function constructReactInstance(
    string $module,
    dict<string, mixed> $attributes,
  ): void {
    $this->jsCall(
      'XHPJS',
      'renderReactElement',
      $this->toJSElementRef(),
      $module,
      $attributes,
    );
  }
}
