<?php

namespace Acme\BlogBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\Form\FormTypeInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Acme\BlogBundle\Exception\InvalidFormException;
use Acme\BlogBundle\Form\PageType;
use Acme\BlogBundle\Model\PageInterface;

use Acme\BlogBundle\Entity\Page;

class ArticleController extends FOSRestController
{
    /**
	 * Get single Page,
	 *
	 * @ApiDoc(
	 *   resource = true,
	 *   description = "Gets a Page for a given id",
	 *   output = "Acme\BlogBundle\Entity\Page",
	 *   statusCodes = {
	 *     200 = "Returned when successful",
	 *     404 = "Returned when the page is not found"
	 *   }
	 * )
	 *
	 * @Annotations\View(templateVar="page")
	 *
	 * @param Request $request the request object
	 * @param int     $id      the page id
	 *
	 * @return array
	 *
	 * @throws NotFoundHttpException when page not exist
	 */
	public function getPageAction($id)
	{
		$page = $this->getDoctrine()
			->getRepository('AcmeBlogBundle:Page')
			->find($id);
		$page = $this->container->get('serializer')->serialize($page, 'json');
		print_r($page);exit;
		$page = new Response($serializedEntity);
		$view = $this->view($page, 200)
            ->setTemplate("AcmeBlogBundle:Article:getPage.html.twig")
            ->setTemplateVar('page')
        ;

        return $this->handleView($view);
	}
	
	public function addPageAction()
	{
		$page = new Page();
		$page->setTitle('A Foo Bar');
		$page->setBody('Lorem ipsum dolor');

		$em = $this->getDoctrine()->getManager();
		$em->persist($page);
		$em->flush();
	}
	
	public function postPageAction(Request $request)
    {
		$post = $request->request->all();
		
		$page = new Page();
		$page->setTitle($post['title']);
		$page->setBody($post['body']);

		$em = $this->getDoctrine()->getManager();
		$em->persist($page);
		$em->flush();
		
		$routeOptions = array(
                'id' => $page->getId(),
                '_format' => $request->get('_format')
            );
			
		return $this->routeRedirectView('api_1_get_page', $routeOptions, Codes::HTTP_CREATED);
        /*try {
            $newPage = $this->container->get('acme_blog.page.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newPage->getId(),
                '_format' => $request->get('_format')
            );

            return $this->routeRedirectView('api_1_get_page', $routeOptions, Codes::HTTP_CREATED);

        } catch (InvalidFormException $exception) {

            return $exception->getForm();
        }*/
    }
	
	/**
	 * Fetch the Page or throw a 404 exception.
	 *
	 * @param mixed $id
	 *
	 * @return PageInterface
	 *
	 * @throws NotFoundHttpException
	 */
	protected function getOr404($id)
	{
		if (!($page = $this->container->get('acme_blog.blog_post.handler')->get($id))) {
			throw new NotFoundHttpException(sprintf('The resource \'%s\' was not found.',$id));
		}

		return $page;
	}
}
