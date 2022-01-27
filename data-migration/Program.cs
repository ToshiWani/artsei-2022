using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Net.Http.Headers;
using System.Text;
using System.Text.RegularExpressions;
using System.Threading.Tasks;
using HtmlAgilityPack;
using Newtonsoft.Json;
using static System.Net.Mime.MediaTypeNames;

namespace Artsei.DataMigration
{
    class Program
    {
        static readonly Dictionary<char, char> _japaneseToLaten = new Dictionary<char, char>
        {
            { '0', '0' },
            { '1', '1' },
            { '2', '2' },
            { '3', '3' },
            { '4', '4' },
            { '5', '5' },
            { '6', '6' },
            { '7', '7' },
            { '8', '8' },
            { '9', '9' },
            { '０', '0' },
            { '１', '1' },
            { '２', '2' },
            { '３', '3' },
            { '４', '4' },
            { '５', '5' },
            { '６', '6' },
            { '７', '7' },
            { '８', '8' },
            { '９', '9' },
        };

        static Task Main(string[] args)
        {
            string wpUserName = Environment.GetEnvironmentVariable("WP_USERNAME");
            string wpPassword = Environment.GetEnvironmentVariable("WP_PASSWORD");

            if (string.IsNullOrWhiteSpace(wpUserName))
            {
                throw new InvalidOperationException("wpUserName must have value");
            }

            if (string.IsNullOrWhiteSpace(wpPassword))
            {
                throw new InvalidOperationException("wpPassword must have value");
            }

            return Run(wpUserName, wpPassword);
        }

        static async Task Run(string username, string password)
        {
            //  enable Shift-JIS encoding

            Encoding.RegisterProvider(CodePagesEncodingProvider.Instance);
            Encoding sjis = Encoding.GetEncoding("shift_jis");

            HtmlWeb web = new HtmlWeb
            {
                OverrideEncoding = sjis,
            };

            //  fetch list of essays

            HtmlDocument topPage = web.Load(new Uri("https://art-sei.com/CONTENTS/DIALY/DIALY.html"));
            IEnumerable<HtmlNode> topPageNodes = topPage.DocumentNode.DescendantsAndSelf();

            var linkPathList = new List<string>();

            foreach (HtmlNode node in topPageNodes)
            {
                if (node.NodeType != HtmlNodeType.Text)
                {
                    continue;
                }

                string text = node.InnerText.Trim();
                if (string.IsNullOrWhiteSpace(text))
                {
                    continue;
                }

                bool hasLink = node.ParentNode
                    ?.Attributes
                    ?.Any(a => a.Name == "onclick" && a.Value.StartsWith("MM_openBrWindow", StringComparison.OrdinalIgnoreCase))
                    ?? false;

                if (!hasLink)
                {
                    continue;
                }

                HtmlAttribute attr = node.ParentNode
                    .Attributes
                    .First(a => a.Name == "onclick" && a.Value.StartsWith("MM_openBrWindow", StringComparison.OrdinalIgnoreCase));

                if (attr == null)
                {
                    continue;
                }

                var linkMatch = Regex.Match(attr.Value, @"MM_openBrWindow\('(.+\.html?)'");
                string path = linkMatch?.Groups[1]?.Value;
                if (!string.IsNullOrWhiteSpace(path))
                {
                    path = path.TrimStart('/').TrimEnd('/').Trim();
                    linkPathList.Add(path);
                }
            }

            Console.WriteLine($"Discovered {linkPathList.Count} links");

            using (var client = new HttpClient())
            {
                // foreach (string path in linkPathList.Where(s => s == "ESSAY001/E486-C003.htm"))
                foreach (string path in linkPathList)
                {
                    Uri uri = new Uri($"https://art-sei.com/CONTENTS/DIALY/{path}");
                    HtmlDocument htmlDoc = web.Load(uri);
                    var nodes = htmlDoc.DocumentNode.DescendantsAndSelf();
                    var sb = new StringBuilder();
                    string title = null;

                    foreach (var n in nodes)
                    {

                        if (n.NodeType != HtmlNodeType.Text)
                        {
                            continue;
                        }

                        string text = n.InnerText.Trim();
                        if (string.IsNullOrWhiteSpace(text) || text == "&nbsp;")
                        {
                            continue;
                        }

                        string tag = n.ParentNode.Name.ToLower().Trim();
                        switch (tag)
                        {
                            case "title":
                                title = text;
                                break;

                            case "p":
                            case "div":
                            case "span":
                                sb.Append($"<!-- wp:paragraph --><{tag}>");
                                sb.Append(text);
                                sb.Append($"</{tag}><!-- /wp:paragraph -->");
                                break;

                            case "font":
                                sb.Append($"<!-- wp:paragraph --><p>{text}</p><!-- /wp:paragraph -->");
                                break;

                            default:
                                break;
                        }
                    }


                    string content = sb.ToString();
                    int year = 1969;
                    int month = 1;
                    int day = 1;


                    Match yearMatch = Regex.Match(content, @"(?<year>\d\d\d\d|\d\d)\s?年");
                    if (yearMatch.Success && yearMatch.Groups.ContainsKey("year"))
                    {
                        year = ToInt(yearMatch.Groups[1].Value) ?? 2004;
                    }
                    else
                    {
                        yearMatch = Regex.Match(content, @"(?<year>\d\d\d\d)");
                        if (yearMatch.Success && yearMatch.Groups.ContainsKey("year"))
                        {
                            year = ToInt(yearMatch.Groups[1].Value) ?? 2004;
                        }
                    }

                    Match monthMatch = Regex.Match(content, @"(?<month>\d\d|\d)\s?月");
                    if (monthMatch.Success && monthMatch.Groups.ContainsKey("month"))
                    {
                        month = ToInt(monthMatch.Groups[1].Value) ?? 1;
                    }

                    Match dayMatch = Regex.Match(content, @"(?<day>\d\d|\d)\s?日");
                    if (dayMatch.Success && dayMatch.Groups.ContainsKey("day"))
                    {
                        day = ToInt(dayMatch.Groups[1].Value) ?? 1;
                    }


                    Console.WriteLine("====== POSTING =======");
                    Console.WriteLine(path);
                    Console.WriteLine(title);
                    Console.WriteLine($"{year}-{month}-{day}");

                    var date = new DateTime(
                        year < 1969 ? 1969 : year,
                        month < 1 ? 1 : month,
                        day < 1 ? 1 : day
                    );

                    await PostAsync(client, new Uri("http://52.55.30.102/wp-json/wp/v2/posts"), username, password, new
                    {
                        status = "publish",
                        title,
                        content,
                        excerpt = "<p>api test excerpt</p>",
                        format = "standard",
                        comment_status = "closed",
                        date = date.ToString("o"),
                        categories = new[] { 10 },
                    });

                    Console.WriteLine("====== SUCCESS POSTING =======");
                }
            }
        }

        static async Task PostAsync(HttpClient client, Uri uri, string username, string password, object body)
        {
            string json = JsonConvert.SerializeObject(body);
            string basicAuth = Convert.ToBase64String(Encoding.UTF8.GetBytes($"{username}:{password}"));
            client.DefaultRequestHeaders.Authorization = new AuthenticationHeaderValue("Basic", basicAuth);

            using (var content = new StringContent(json, Encoding.UTF8, Application.Json))
            using (HttpResponseMessage response = await client.PostAsync(uri, content))
            {
                if (response.IsSuccessStatusCode)
                {
                    Console.WriteLine("Successfully posted the article");
                }
                else
                {
                    string errorMessage = await response.Content.ReadAsStringAsync();
                    Console.WriteLine($"Failed to post the article!");
                    Console.WriteLine(errorMessage);
                }
            }
        }

        static int? ToInt(string str)
        {
            int? result = null;

            if (!string.IsNullOrWhiteSpace(str))
            {
                char[] replaced = new char[str.Length];
                for (int i = 0; i < str.Length; i++)
                {
                    replaced[i] = _japaneseToLaten[str[i]];
                }

                result = int.Parse(new string(replaced));
            }

            return result;
        }

    }
}
