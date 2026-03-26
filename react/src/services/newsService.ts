export type RelatedArticlesResponse = {
  related: {
    id: number;
    title: string;
    link: string;
    image?: string;
    source: string;
    pubDate: string;
  }[];
  page: number;
  perPage: number;
  total: number;
  aiPending: boolean;
};

export async function getRelatedArticles(
  articleId: number,
  page: number = 1,
  perPage: number = 5
): Promise<RelatedArticlesResponse> {
  try {
    const res = await fetch(
      `http://localhost/KLTN_CaoBao/BE/modules/news/API_keyGemini.php?id=${articleId}&page=${page}&perPage=${perPage}`
    );
    const data = await res.json();
    if (data.error) throw new Error(data.error);
    return data;
  } catch (err) {
    console.error("Lỗi khi lấy bài liên quan:", err);
    return { related: [], page, perPage, total: 0, aiPending: false };
  }
}