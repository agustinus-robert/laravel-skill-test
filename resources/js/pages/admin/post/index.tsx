import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';

interface User {
    id: number;
    name: string;
}

interface Post {
    id: number;
    title: string;
    status: string;
    is_draft: boolean;
    published_at: string | null;
    updated_at: string;
    user: User;
}

interface Props {
    posts: {
        data: Post[];
        current_page: number;
        last_page: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Posting',
        href: '/admin/posts',
    },
];

export default function Index({ posts }: Props) {
    const params = new URLSearchParams(window.location.search);
    const message = params.get('message');

    const destroy = (id: number) => {
        if (!confirm('Are you sure you want to delete this post?')) {
            return;
        }

        router.delete(route('admin.posts.destroy', id), {
            onSuccess: () => {
                router.visit('/admin/posts?message=deleted');
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Posts" />

            <div className="p-6">
                {message === 'added' && (
                    <div className="mb-4 rounded-md border border-green-300 bg-green-100 p-3 text-green-800">Data added successfully</div>
                )}

                {message === 'updated' && (
                    <div className="mb-4 rounded-md border border-green-300 bg-green-100 p-3 text-green-800">Data updated successfully</div>
                )}

                {message === 'deleted' && (
                    <div className="mb-4 rounded-md border border-green-300 bg-green-100 p-3 text-green-800">Data deleted successfully</div>
                )}

                <div className="mb-6 flex items-center justify-between">
                    <h1 className="text-2xl font-bold">Posts</h1>

                    <Button asChild>
                        <Link href={route('admin.posts.create')}>Create Post</Link>
                    </Button>
                </div>

                <div className="rounded-lg border">
                    <table className="w-full">
                        <thead>
                            <tr className="border-b">
                                <th className="p-3 text-left">Title</th>

                                <th className="p-3 text-left">Author</th>

                                <th className="p-3 text-left">Status</th>

                                <th className="p-3 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {posts.data.map((post) => (
                                <tr key={post.id} className="border-b">
                                    <td className="p-3">{post.title}</td>

                                    <td className="p-3">{post.user.name}</td>

                                    <td className="p-3">{post.status}</td>

                                    <td className="p-3 text-right">
                                        <div className="flex justify-end gap-2">
                                            <Button variant="outline" asChild>
                                                <Link href={route('admin.posts.edit', post.id)}>Edit</Link>
                                            </Button>

                                            <Button variant="destructive" onClick={() => destroy(post.id)}>
                                                Delete
                                            </Button>
                                        </div>
                                    </td>
                                </tr>
                            ))}

                            {posts.data.length === 0 && (
                                <tr>
                                    <td colSpan={4} className="p-6 text-center">
                                        No posts found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="mt-6 flex items-center justify-between">
                    <Button variant="outline" disabled={!posts.prev_page_url} asChild={!!posts.prev_page_url}>
                        {posts.prev_page_url ? <Link href={posts.prev_page_url}>Previous</Link> : <span>Previous</span>}
                    </Button>

                    <div className="text-sm">
                        Page {posts.current_page} of {posts.last_page}
                    </div>

                    <Button variant="outline" disabled={!posts.next_page_url} asChild={!!posts.next_page_url}>
                        {posts.next_page_url ? <Link href={posts.next_page_url}>Next</Link> : <span>Next</span>}
                    </Button>
                </div>
            </div>
        </AppLayout>
    );
}
