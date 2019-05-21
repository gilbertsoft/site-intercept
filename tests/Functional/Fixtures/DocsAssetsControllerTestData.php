<?php

namespace App\Tests\Functional\Fixtures;

use App\Entity\DocsServerRedirect;
use App\Entity\DocumentationJar;
use App\Enum\DocumentationStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DocsAssetsControllerTestData extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Home page
        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/TYPO3-Documentation/DocsTypo3Org-Homepage.git')
            ->setVendor('typo3')
            ->setName('docs-homepage')
            ->setPackageName('typo3/docs-homepage')
            ->setPackageType('typo3-cms-documentation')
            ->setExtensionKey('docs_that_is_no_extension')
            ->setBranch('new_docs_server')
            ->setTargetBranchDirectory('master')
            ->setTypeLong('docs-home')
            ->setTypeShort('h')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('https://raw.githubusercontent.com/TYPO3-Documentation/DocsTypo3Org-Homepage/master/composer.json');
        $manager->persist($documentationJar);

        // Core extension
        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/TYPO3-CMS/felogin.git')
            ->setVendor('typo3')
            ->setName('cms-felogin')
            ->setPackageName('typo3/cms-felogin')
            ->setPackageType('typo3-cms-framework')
            ->setExtensionKey('felogin')
            ->setBranch('master')
            ->setTargetBranchDirectory('master')
            ->setTypeLong('core-extension')
            ->setTypeShort('c')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('');
        $manager->persist($documentationJar);

        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/TYPO3-CMS/felogin.git')
            ->setVendor('typo3')
            ->setName('cms-felogin')
            ->setPackageName('typo3/cms-felogin')
            ->setPackageType('typo3-cms-framework')
            ->setExtensionKey('felogin')
            ->setBranch('draft')
            ->setTargetBranchDirectory('draft')
            ->setTypeLong('core-extension')
            ->setTypeShort('c')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('');
        $manager->persist($documentationJar);

        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/TYPO3-CMS/felogin.git')
            ->setVendor('typo3')
            ->setName('cms-felogin')
            ->setPackageName('typo3/cms-felogin')
            ->setPackageType('typo3-cms-framework')
            ->setExtensionKey('felogin')
            ->setBranch('9.5')
            ->setTargetBranchDirectory('9.5')
            ->setTypeLong('core-extension')
            ->setTypeShort('c')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('');
        $manager->persist($documentationJar);

        // Community extension
        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/TYPO3GmbH/blog.git')
            ->setVendor('t3g')
            ->setName('blog')
            ->setPackageName('t3g/blog')
            ->setPackageType('typo3-cms-extension')
            ->setExtensionKey('blog')
            ->setBranch('master')
            ->setTargetBranchDirectory('master')
            ->setTypeLong('extension')
            ->setTypeShort('p')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('');
        $manager->persist($documentationJar);

        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/TYPO3GmbH/blog.git')
            ->setVendor('t3g')
            ->setName('blog')
            ->setPackageName('t3g/blog')
            ->setPackageType('typo3-cms-extension')
            ->setExtensionKey('blog')
            ->setBranch('draft')
            ->setTargetBranchDirectory('draft')
            ->setTypeLong('extension')
            ->setTypeShort('p')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('');
        $manager->persist($documentationJar);

        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/TYPO3GmbH/blog.git')
            ->setVendor('t3g')
            ->setName('blog')
            ->setPackageName('t3g/blog')
            ->setPackageType('typo3-cms-extension')
            ->setExtensionKey('blog')
            ->setBranch('8.7.4')
            ->setTargetBranchDirectory('8.7')
            ->setTypeLong('extension')
            ->setTypeShort('p')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('');
        $manager->persist($documentationJar);

        // Community extension (master only)
        $documentationJar = (new DocumentationJar())
            ->setRepositoryUrl('https://github.com/georgringer/news.git')
            ->setVendor('georgringer')
            ->setName('news')
            ->setPackageName('georgringer/news')
            ->setPackageType('typo3-cms-extension')
            ->setExtensionKey('news')
            ->setBranch('master')
            ->setTargetBranchDirectory('master')
            ->setTypeLong('extension')
            ->setTypeShort('p')
            ->setStatus(DocumentationStatus::STATUS_RENDERED)
            ->setBuildKey('')
            ->setPublicComposerJsonUrl('');
        $manager->persist($documentationJar);
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}
