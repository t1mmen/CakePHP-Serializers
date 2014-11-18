<?php
/**
 *
 * @package  Serializers.Routing.Filter
 */
App::uses('DispatcherFilter', 'Routing');

class DeserializerUnkownObject extends Exception {}

/**
 *
 */
class DeserializerFilter extends DispatcherFilter {

	/**
	 * Method called before the controller is instantiated and called to serve a
	 * request. If used with default priority, it will be called after the Router
	 * has parsed the URL and set the routing params into the request object.
	 *
	 * We will process the request for the json data and de-serialize it
	 *
	 * @param  CakeEvent $event [description]
	 * @return [type]           [description]
	 */
	public function beforeDispatch(CakeEvent $event) {
		// get the request data
		$request = $event->data['request'];
		$controllerRequested = $request->params['controller'];
		$data = $request->input('json_decode');

		if (!empty($data)) {
			$data = $this->_deserializeData($data, $controllerRequested);
		}

		$request->data = $data;
	}

	/**
	 * deseralize the response and turn it into something that cake expects
	 *
	 * @param  array  $data the serialized data
	 * @return array
	 */
	protected function _deserializeData($serializedData = array(), $controllerRequested) {
		$serializedObjectProperties = get_object_vars($serializedData);

		if (!array_key_exists($controllerRequested, (array)$serializedObjectProperties)) {
			$msg = "The controller name: $controllerRequested was not included in the passed in JSON body of the request.";
			throw new DeserializerUnkownObject($msg);
		}

		$dataAsArray = get_object_vars($serializedData->{$controllerRequested});
		$dataAsArray['ResinProduct'] = $dataAsArray;

		return $dataAsArray;
	}
}
